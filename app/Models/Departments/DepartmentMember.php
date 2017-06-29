<?php

namespace App\Models\Departments;

use Illuminate\Database\Eloquent\Model;

class DepartmentMember extends Model
{
    protected $fillable = ['department_id', 'member_id', 'roleId', 'createdBy'];
}
