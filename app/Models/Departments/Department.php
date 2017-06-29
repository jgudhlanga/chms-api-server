<?php

namespace App\Models\Departments;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    protected $fillable = ['name', 'alias', 'description', 'duties', 'hod', 'createdBy', 'dateFormed', 'isActive'];

    public function departmentMembers() {
        return $this->hasMany('App\Models\Departments\DepartmentMember');
    }

    public function addMember(DepartmentMember $member) {
        return $this->departmentMembers()->save($member);
    }

    public function delete() {
        $this->departmentMembers()->delete();
        Parent::delete();
    }

}
