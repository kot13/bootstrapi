<?php
namespace App\Common;

use App\Model\Log;

trait LoggingTrait {
    public static function bootLoggingTrait()
    {
        if(Auth::checkUser()) {
            static::created(function ($item) {
                Log::create([
                    'action' => 'CREATE',
                    'entity_id' => $item->id,
                    'entity_type' => get_class($item),
                    'state' => $item->toJson(),
                    'created_by' => Auth::getUserId(),
                ]);
            });

            static::updated(function ($item) {
                Log::create([
                    'action' => 'UPDATE',
                    'entity_id' => $item->id,
                    'entity_type' => get_class($item),
                    'state' => $item->toJson(),
                    'created_by' => Auth::getUserId(),
                ]);
            });

            static::deleted(function ($item) {
                Log::create([
                    'action' => 'DELETE',
                    'entity_id' => $item->id,
                    'entity_type' => get_class($item),
                    'created_by' => Auth::getUserId(),
                ]);
            });

            static::restored(function ($item) {
                Log::create([
                    'action' => 'RESTORE',
                    'entity_id' => $item->id,
                    'entity_type' => get_class($item),
                    'created_by' => Auth::getUserId(),
                ]);
            });
        }
    }
}