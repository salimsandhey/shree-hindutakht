<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DonationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'ifsc_code',
        'branch',
        'upi_id',
        'message',
        'qr_code_path',
    ];

    protected $appends = [
        'qr_code_url'
    ];

    // Accessor to get the full URL of the QR code
    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code_path) {
            return asset('storage/' . $this->qr_code_path);
        }
        
        return null;
    }

    // Get the latest donation setting record
    public static function getSettings()
    {
        return self::latest()->first() ?? new self();
    }
}