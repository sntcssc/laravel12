<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Role extends Model
// {
//     //
// }

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends SpatieRole
{
    use SoftDeletes;
}
