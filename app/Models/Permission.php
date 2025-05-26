<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Permission extends Model
// {
//     //
// }

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends SpatiePermission
{
    use SoftDeletes;
}
