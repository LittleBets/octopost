<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

if (!function_exists('shortDateAndTime')) {
    function shortDateAndTime($dateTime, $timezone = null)
    {
        $dateTime = userTimeZone($dateTime, $timezone);
        $diffInHours = userTimeZone(Carbon::now(), $timezone)->diffInHours($dateTime);
        switch (true) {
            case $diffInHours < 24:
                $submittedAtShort = $dateTime?->format('g:i A');
                break;
            case $diffInHours < 48:
                $submittedAtShort = $dateTime?->diffForHumans();
                break;
            default:
                $submittedAtShort = $dateTime?->format('M d');
                break;
        }
        return $submittedAtShort;
    }
}

if (!function_exists('userTimeZone')) {
    function userTimeZone(Carbon $dateTime, $userTimezone = null): Carbon
    {
        $timezone = $userTimezone ?? Config::get('app.timezone');
        return $dateTime->timezone($timezone);
    }
}
