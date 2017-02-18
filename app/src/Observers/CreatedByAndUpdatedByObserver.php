<?php
namespace App\Observers;

use App\Model\BaseModel;
use App\Common\Auth;

final class CreatedByAndUpdatedByObserver extends BaseObserver
{
    /**
     * Listen to the BaseModel creating event.
     *
     * @param  BaseModel  $model
     * @return void
     */
    public function creating(BaseModel $model)
    {
        $user = Auth::getUser();
        if (!is_null($user)) {
            $model->created_by = $user->id;
        }
    }


    /**
     * Listen to the BaseModel updating event.
     *
     * @param  BaseModel  $model
     * @return void
     */
    public function updating(BaseModel $model)
    {
        $user = Auth::getUser();
        if (!is_null($user)) {
            $model->updated_by = $user->id;
        }
    }
}
