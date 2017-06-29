<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {

    protected $fillable = ['firstName', 'surname', 'middleName', 'title', 'gender', 'mobileNumber',
        'altNumber', 'homeNumber', 'workNumber', 'email', 'birthDate', 'maritalStatus', 'faxNumber',
        'postalAddress', 'postalCode', 'residentialAddress', 'surbub', 'city', 'province', 'profilePicture'];

    public function believerInfo(){
        return $this->hasOne('App\Models\Members\Believer');
    }
    
    public function departments(){
        return $this->hasMany('App\Models\Departments\DepartmentMember');
    }

    public function groups() {
        return $this->hasMany('App\Models\CellGroups\GroupMember');
    }

    public function families() {
        return $this->hasMany('App\Models\Families\FamilyMember');
    }
    
    public function qualifications(){
        return $this->hasMany('App\Models\Members\Qualification');
    }
    
    public function skills(){
        return $this->hasMany('App\Models\Members\Skill');
    }
    
    public function addQualification(Qualification $qualification){
        return $this->qualifications()->save($qualification);
    }
    
     public function addSkill(Skill $skill){
        return $this->skills()->save($skill);
    }
}
