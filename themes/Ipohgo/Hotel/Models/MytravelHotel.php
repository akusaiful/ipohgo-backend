<?php


namespace Themes\Mytravel\Hotel\Models;


class MytravelHotel extends \Modules\Hotel\Models\Hotel
{

    protected $casts = [
        'policy' => 'array',
        'extra_price' => 'array',
        'service_fee' => 'array',
        'surrounding' => 'array',
        'badge_tags' => 'array',
    ];

}
