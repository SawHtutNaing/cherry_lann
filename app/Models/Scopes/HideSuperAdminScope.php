<?php

// app/Models/Scopes/HideSuperAdminScope.php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HideSuperAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('role', '!=', 'super_admin');
    }
}
