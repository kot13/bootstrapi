<?php
namespace App\Common;

trait CreatedByUpdatedByTrait {
    public static function bootCreatedByUpdatedByTrait()
    {
        if(Auth::checkUser()) {
            static::creating(function ($item) {
                $item->created_by = Auth::getUserId();
            });

            static::updating(function ($item) {
                $item->updated_by = Auth::getUserId();
            });
        }
    }
}