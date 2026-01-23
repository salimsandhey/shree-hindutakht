<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of events with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        
        $events = Event::orderBy('event_date', 'desc')
                      ->paginate($perPage);
        
        // Add full URL for featured images
        $events->getCollection()->transform(function ($event) {
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
     * Store a newly created event
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'registration_deadline' => 'nullable|date',
            'location' => 'required|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_featured' => 'nullable|boolean',
            'featured_image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $eventData = $request->only([
            'title', 'description', 'event_date', 'registration_deadline', 'location', 
            'max_participants', 'status'
        ]);
        
        // Handle boolean fields
        $eventData['is_featured'] = $request->boolean('is_featured', false);

        $event = Event::create($eventData);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events/images', 'public');
            $event->featured_image = $path;
            $event->save();
        }

        // Add full URL for featured image
        if ($event->featured_image) {
            $event->featured_image_url = asset('storage/' . $event->featured_image);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ]);
    }

    /**
     * Display the specified event
     */
    public function show(Event $event): JsonResponse
    {
        // Add full URL for featured image
        if ($event->featured_image) {
            $event->featured_image_url = asset('storage/' . $event->featured_image);
        }
        
        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'event_date' => 'sometimes|required|date',
            'registration_deadline' => 'nullable|date',
            'location' => 'sometimes|required|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_featured' => 'nullable|boolean',
            'featured_image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $eventData = $request->only([
            'title', 'description', 'event_date', 'registration_deadline', 'location', 
            'max_participants'
        ]);
        
        // Handle boolean fields
        if ($request->has('is_featured')) {
            $eventData['is_featured'] = $request->boolean('is_featured');
        }

        $event->update($eventData);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($event->featured_image) {
                $storagePath = 'public/' . $event->featured_image;
                if (Storage::exists($storagePath)) {
                    Storage::delete($storagePath);
                }
            }
            
            $path = $request->file('featured_image')->store('events/images', 'public');
            $event->featured_image = $path;
            $event->save();
        }

        // Add full URL for featured image
        if ($event->featured_image) {
            $event->featured_image_url = asset('storage/' . $event->featured_image);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event): JsonResponse
    {
        // Delete featured image if exists
        if ($event->featured_image) {
            $storagePath = 'public/' . $event->featured_image;
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
        }
        
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}