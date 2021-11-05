<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'display_name'];

    /**
     *
     * Get all permissions references
     *
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

}
