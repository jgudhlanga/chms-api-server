<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    
    protected $fillable = ['name', 'description', 'field_id', 'member_id', 'createdBy'];
}
