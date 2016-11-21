<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MaxPerPageScope implements Scope
{
    /**
     * Maximum count items in response
     */
    const MAX_PER_PAGE = 100;

    public function apply(Builder $builder, Model $model)
    {
        return $builder->take(self::MAX_PER_PAGE);
    }
}