<?php 

use Carbon\Carbon;


if (!function_exists('formatNotificationTime')) {
    function formatNotificationTime($timestamp)
    {
        $time = Carbon::parse($timestamp);

        if ($time->isToday()) {
            return $time->format('h:i A'); // e.g., 03:45 PM
        }

        if ($time->isYesterday()) {
            return 'Yesterday';
        }

        if ($time->isCurrentYear()) {
            return $time->format('M d'); // e.g., Apr 12
        }

        return $time->format('M d, Y'); // e.g., Apr 12, 2024
    }
}