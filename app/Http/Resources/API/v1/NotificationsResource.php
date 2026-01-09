<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationsResource extends ResourceCollection
{
//    public static $wrap = 'data';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
