<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class AvatarService
{
    /**
     * Generate an avatar image from user's name
     *
     * @param string $name
     * @param int $size
     * @return string Path to the generated avatar
     */
    public static function generateAvatar(string $name, int $size = 200): string
    {
        // Always use SVG generation as it doesn't require GD extension
        return self::generateSvgAvatar($name, $size);
    }
    
    /**
     * Get initials from full name
     *
     * @param string $name
     * @return string
     */
    private static function getInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
                if (strlen($initials) >= 2) break; // Max 2 initials
            }
        }
        
        return $initials ?: 'U'; // Default to 'U' for User
    }
    
    /**
     * Generate a random pleasant color for background
     *
     * @return string
     */
    private static function generateRandomColor(): string
    {
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57',
            '#FF9FF3', '#54A0FF', '#5F27CD', '#FD79A8', '#FDCB6E',
            '#6C5CE7', '#A29BFE', '#74B9FF', '#0984E3', '#00B894'
        ];
        
        return $colors[array_rand($colors)];
    }
    
    /**
     * Get contrasting text color (white or black) based on background
     *
     * @param string $backgroundColor
     * @return string
     */
    private static function getContrastColor(string $backgroundColor): string
    {
        // Convert hex to RGB
        $hex = str_replace('#', '', $backgroundColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return white for dark backgrounds, black for light backgrounds
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }
    
    /**
     * Create SVG avatar as alternative (lighter weight)
     *
     * @param string $name
     * @param int $size
     * @return string
     */
    public static function generateSvgAvatar(string $name, int $size = 200): string
    {
        $initials = self::getInitials($name);
        $backgroundColor = self::generateRandomColor();
        $textColor = self::getContrastColor($backgroundColor);
        $fontSize = $size * 0.4;
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '" xmlns="http://www.w3.org/2000/svg">
            <rect width="' . $size . '" height="' . $size . '" fill="' . $backgroundColor . '"/>
            <text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="' . $textColor . '" font-family="Arial, sans-serif" font-size="' . $fontSize . '" font-weight="bold">' . $initials . '</text>
        </svg>';
        
        $filename = 'avatars/avatar_' . md5($name . time()) . '.svg';
        Storage::disk('public')->put($filename, $svg);
        
        return $filename;
    }
}