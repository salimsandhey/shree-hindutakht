<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\DonationSetting;

class DonationController extends Controller
{
    /**
     * Get donation details
     */
    public function index(): JsonResponse
    {
        try {
            // Get donation details from database
            $donationSetting = DonationSetting::getSettings();
            
            $donationDetails = [
                'bank_details' => [
                    'bank_name' => $donationSetting->bank_name,
                    'account_name' => $donationSetting->account_name,
                    'account_number' => $donationSetting->account_number,
                    'ifsc_code' => $donationSetting->ifsc_code,
                    'branch' => $donationSetting->branch
                ],
                'qr_code' => $donationSetting->qr_code_url,
                'upi_id' => $donationSetting->upi_id,
                'message' => $donationSetting->message
            ];
            
            return response()->json([
                'success' => true,
                'data' => $donationDetails
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
              ->header('Pragma', 'no-cache')
              ->header('Expires', '0');
        } catch (\Exception $e) {
            \Log::error('Error fetching donation details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching donation details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update donation details
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'bank_name' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'ifsc_code' => 'nullable|string|max:20',
                'branch' => 'nullable|string|max:255',
                'upi_id' => 'nullable|string|max:100',
                'message' => 'nullable|string|max:500',
                'qr_code' => 'nullable|file|mimes:png,jpg,jpeg|max:2048'
            ]);

            // Get existing donation setting or create new one
            $donationSetting = DonationSetting::first() ?? new DonationSetting();
            
            // Update bank details
            $donationSetting->bank_name = $validatedData['bank_name'] ?? $donationSetting->bank_name;
            $donationSetting->account_name = $validatedData['account_name'] ?? $donationSetting->account_name;
            $donationSetting->account_number = $validatedData['account_number'] ?? $donationSetting->account_number;
            $donationSetting->ifsc_code = $validatedData['ifsc_code'] ?? $donationSetting->ifsc_code;
            $donationSetting->branch = $validatedData['branch'] ?? $donationSetting->branch;
            
            // Update UPI details
            $donationSetting->upi_id = $validatedData['upi_id'] ?? $donationSetting->upi_id;
            $donationSetting->message = $validatedData['message'] ?? $donationSetting->message;
            
            // Handle QR code upload
            if ($request->hasFile('qr_code')) {
                // Delete old QR code if exists
                if ($donationSetting->qr_code_path && Storage::exists('public/' . $donationSetting->qr_code_path)) {
                    Storage::delete('public/' . $donationSetting->qr_code_path);
                }
                
                // Store new QR code
                $path = $request->file('qr_code')->store('donations', 'public');
                $donationSetting->qr_code_path = $path;
            }
            
            // Save updated donation details
            $donationSetting->save();
            
            // Format response data to match the old structure
            $responseData = [
                'bank_details' => [
                    'bank_name' => $donationSetting->bank_name,
                    'account_name' => $donationSetting->account_name,
                    'account_number' => $donationSetting->account_number,
                    'ifsc_code' => $donationSetting->ifsc_code,
                    'branch' => $donationSetting->branch
                ],
                'qr_code' => $donationSetting->qr_code_url,
                'upi_id' => $donationSetting->upi_id,
                'message' => $donationSetting->message
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Donation details updated successfully',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating donation details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating donation details: ' . $e->getMessage()
            ], 500);
        }
    }
}