<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\NotificationsResource;
use App\Traits\generateAPI;

class NotificationsController extends Controller
{
    use generateAPI;

    public function getNotifications(){

        $user = auth()->user();

//        return $user;

        try{
            $user->unreadNotifications->markAsRead();
        }catch (\Exception $e){}

        return (new NotificationsResource($user->notifications()->paginate(20)));
    }

    public function count(){
        return $this->success([
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    }
}
