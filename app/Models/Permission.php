<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'name',
      'path_name',
      'parent_id',
      'icon_class',
      'icon'
    ];

    /**
     *
     * Get all references roles
     *
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
