<?php

namespace App\Transformers;

use App\Models\Members\Member;
use App\Models\Departments\Department;
use App\Models\Departments\DepartmentMember;
use League\Fractal\TransformerAbstract;

class DepartmentMemberTransformer extends TransformerAbstract {

    
    public function transform(DepartmentMember $departmentMember) {
        //get the member names
        $member = Member::find($departmentMember->member_id);
        //get the department
        $department = Department::find($departmentMember->department_id);
        return [
            'id' => $departmentMember->id,
            'departmentId' => $departmentMember->department_id,
            'memberId' => $departmentMember->member_id,
            'roleId' => $departmentMember->roleId,
            'createdBy' => $departmentMember->createdBy,
            'dateFormed' => $departmentMember->dateFormed,
            'memberName' => (isset($member->firstName)) ? $member->firstName : 'not set',
            'memberSurname' => (isset($member->surname)) ? $member->surname : 'not set',
            'memberInitial' => (isset($member->firstName)) ? substr($member->firstName, 0, 1) : '',
            'departmentName' => isset($department->name) ? $department->name : '',
        ];
    }

}
