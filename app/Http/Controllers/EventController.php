<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRsvp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Get upcoming events
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $member = auth('api')->user();

        $events = Event::upcoming()
            ->orderBy('event_date', 'asc')
            ->paginate($perPage);

        // Add user RSVP status to each event
        $events->getCollection()->transform(function ($event) use ($member) {
            $rsvp = $event->getMemberRsvp($member->id);
            $event->user_rsvp = $rsvp ? $rsvp->response : null;
            // Add full URL for featured image
            if ($event->featured_image) {
                $event->featured_image_url = asset('storage/' . $event->featured_image);
            } else {
                $event->featured_image_url = null;
            }
            return $event;
        });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get specific event details
     */
    public function show(Event $event): JsonResponse
    {
        $member = auth('api')->user();
        
        $event->load(['rsvps.member:id,name,photo']);
        $rsvp = $event->getMemberRsvp($member->id);
        $event->user_rsvp = $rsvp ? $rsvp->response : null;
        // Add full URL for featured image
        if ($event->featured_image) {
            $event->featured_image_url = asset('storage/' . $event->featured_image);
        } else {
            $event->featured_image_url = null;
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * RSVP to an event
     */
    public function rsvp(Request $request, Event $event): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'response' => 'required|in:interested,going',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $member = auth('api')->user();
        $response = $request->response;
        
        // Check if event is still open for RSVP
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return response()->json([
                'success' => false,
                'message' => 'Registration deadline has passed'
            ], 400);
        }

        // Check if event has reached max participants for 'going' response
        if ($response === 'going' && $event->max_participants && $event->going_count >= $event->max_participants) {
            return response()->json([
                'success' => false,
                'message' => 'Event has reached maximum participants'
            ], 400);
        }

        $existingRsvp = EventRsvp::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existingRsvp) {
            // Update existing RSVP and adjust counts
            $oldResponse = $existingRsvp->response;
            
            if ($oldResponse !== $response) {
                // Decrement old count
                if ($oldResponse === 'interested') {
                    $event->decrementInterested();
                } else {
                    $event->decrementGoing();
                }
                
                // Increment new count
                if ($response === 'interested') {
                    $event->incrementInterested();
                } else {
                    $event->incrementGoing();
                }
            }
            
            $existingRsvp->update([
                'response' => $response,
                'notes' => $request->notes,
            ]);
            
            $message = 'RSVP updated successfully';
        } else {
            // Create new RSVP
            EventRsvp::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'response' => $response,
                'notes' => $request->notes,
            ]);
            
            // Increment count
            if ($response === 'interested') {
                $event->incrementInterested();
            } else {
                $event->incrementGoing();
            }
            
            $message = 'RSVP created successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'response' => $response,
                'interested_count' => $event->fresh()->interested_count,
                'going_count' => $event->fresh()->going_count,
            ]
        ]);
    }

    /**
     * Get RSVPs for an event
     */
    public function getRsvps(Request $request, Event $event): JsonResponse
    {
        $response = $request->get('response'); // 'interested' or 'going'
        $perPage = $request->get('per_page', 20);

        $query = EventRsvp::where('event_id', $event->id)
            ->with('member:id,name,photo,member_id');

        if ($response) {
            $query->where('response', $response);
        }

        $rsvps = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $rsvps
        ]);
    }
}