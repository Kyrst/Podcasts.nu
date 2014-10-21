<?php
class Banner_View extends Eloquent
{
    protected $table = 'banner_views';



    public static function format_seconds($seconds)
    {
        $seconds = round($seconds);

        $minutes = floor($seconds / 60);
        $minutes = ($minutes >= 10) ? $minutes : '0' . $minutes;

        $seconds = floor($seconds % 60);
        $seconds = ($seconds >= 10) ? $seconds : '0' . $seconds;

        return $minutes . ':' . $seconds;
    }

}