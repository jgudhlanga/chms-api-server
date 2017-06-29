<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $fillable = ['name', 'subjects', 'field_id', 'member_id', 
        'createdBy', 'institution', 'yearObtained'
        ];
}
