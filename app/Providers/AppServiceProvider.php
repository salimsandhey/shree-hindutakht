<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set the public path for Hostinger environments
        if (env('FILESYSTEM_DISK') === 'hostinger') {
            $this->app->bind('path.public', function() {
                // Check if public_html directory exists
                $publicHtmlPath = base_path('public_html');
                if (is_dir($publicHtmlPath)) {
                    return $publicHtmlPath;
                }
                
                // Fallback to default public path
                return base_path('public');
            });
        }
    }

    /**
     * Get the correct storage path for Hostinger environments
     *
     * @return string
     */
    public static function getHostingerStoragePath()
    {
        // Check if HOSTINGER_STORAGE_PATH is set in environment
        $envPath = env('HOSTINGER_STORAGE_PATH');
        if ($envPath) {
            return $envPath;
        }

        // Try to detect the correct path for Hostinger
        $basePath = base_path();
        
        // Check if we're in a typical Hostinger setup
        if (strpos($basePath, '/home/') === 0) {
            // We're on a Linux server, likely Hostinger
            // The structure is typically:
            // /home/userid/domains/domainname.com/hindutakht
            // /home/userid/domains/domainname.com/public_html
            
            $parts = explode('/', $basePath);
            if (count($parts) >= 5 && $parts[1] === 'home') {
                // Reconstruct the domain path: /home/userid/domains/domainname.com
                $basePathParts = array_slice($parts, 0, 5);
                $domainPath = implode('/', $basePathParts);
                $potentialPublicHtml = $domainPath . '/public_html';
                
                if (is_dir($potentialPublicHtml)) {
                    return $potentialPublicHtml . '/storage';
                }
                
                // Alternative: check if public_html is in the same parent directory
                $appParentDir = dirname($basePath);
                $alternativePublicHtml = $appParentDir . '/public_html';
                if (is_dir($alternativePublicHtml)) {
                    return $alternativePublicHtml . '/storage';
                }
            }
        }
        
        // Fallback to relative path
        return dirname($basePath) . '/public_html/storage';
    }
}