<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckAppVersionMiddleware
{
    public function handle($request, Closure $next)
    {

        $deviceType = $request->header('device-type');
        $appVersion = $request->header('app-version');

        $settings = Setting::first();
        // Get the minimum required app version for the given device type from the database or configuration file

        if ($deviceType == 'ios') {
            $minVersion = $settings->latest_ios_version;
            $appLink =  $settings->app_store;
        }


        if ($deviceType == 'android') {
            $minVersion = $settings->latest_android_version;
            $appLink =  $settings->google_play;
        }


        if(!$appVersion ) {
            $message = __('site.message_for_version_of_app');
            return response()->json([
                'message' => $message,
                'app_link' => $appLink,
            ], 426); // Upgrade required
        }

        if ($this->versionToInt($appVersion) < $this->versionToInt($minVersion)) {
            // App version is lower than the minimum required version
            $message = __('site.message_for_version_of_app');
            return response()->json([
                'message' => $message,
                'app_link' => $appLink,
            ], 426); // Upgrade required
        }

        // App version is up to date, continue with the request
        return $next($request);
    }

    /**
     * Convert a version string to an integer format for comparison.
     *
     * @param string $version The version string to convert.
     * @return int The version as an integer.
     */
    protected function versionToInt($version)
    {
        $version = str_replace('.', '', $version);
        return intval($version);
    }
}
