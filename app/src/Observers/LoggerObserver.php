<?php
namespace App\Observers;

use App\Model\BaseModel;
use App\Model\Log;
use App\Common\Auth;

final class LoggerObserver extends BaseObserver
{
    /**
     * Listen to the BaseModel created event.
     *
     * @param  BaseModel  $model
     * @return void
     */
    public function created(BaseModel $model){
        $this->logEvent('CREATE', $model);
    }

    /**
     * Listen to the BaseModel updated event.
     *
     * @param  BaseModel  $model
     * @return void
     */
    public function updated(BaseModel $model){
        $this->logEvent('UPDATE', $model);
    }

    /**
     * Listen to the BaseModel deleted event.
     *
     * @param  BaseModel  $model
     * @return void
     */
    public function deleted(BaseModel $model){
        $this->logEvent('DELETE', $model);
    }

    /**
     * Listen to the BaseModel restored event.
     *
     * @param  BaseModel  $model
     * @return void
     */
    public function restored(BaseModel $model){
        $this->logEvent('RESTORE', $model);
    }

    /**
     * @param string $event
     * @param BaseModel $model
     */
    private function logEvent($event, BaseModel $model){
        $user = Auth::getUser();

        if(!is_null($user)){

            Log::create([
                'action'      => $event,
                'entity_id'   => $model->id,
                'entity_type' => get_class($model),
                'state'       => $model->toJson(),
                'created_by'  => $user->id,
            ]);

        }
    }
}