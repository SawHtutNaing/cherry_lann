<?php

// app/Auth/UnscopedUserProvider.php
namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;

class UnscopedUserProvider extends EloquentUserProvider
{
    protected function newModelQuery($model = null)
    {
        return is_null($model)
            ? $this->createModel()->newQuery()->withoutGlobalScopes()
            : $model->newQuery()->withoutGlobalScopes();
    }
}
