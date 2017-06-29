<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'description', 'cretedBy', 'lastUpdatedBy', 'isActive'];
}
