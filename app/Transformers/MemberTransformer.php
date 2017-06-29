<?php

namespace App\Transformers;

use App\Models\Members\Member;
use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'groups', 'families', 'qualifications', 'skills', 'departments'
    ];

    public function transform(Member $member) {
        $birthDate = ($member->birthDate > 0) ? date('d-M-Y', $member->birthDate) : 'Not set';
        $title = ($member->title != '') ? $member->title . '. ' : '';
        return [
            'id' => $member->id,
            'title' => $member->title,
            'gender' => $member->gender,
            'maritalStatus' => $member->maritalStatus,
            'firstName' => $member->firstName,
            'initials' => substr($member->firstName, 0, 1) . '.',
            'surname' => $member->surname,
            'name' => $title . $member->firstName . ' ' . $member->surname,
            'middleName' => $member->middleName,
            'alias' => $member->alias,
            'email' => $member->email,
            'birthDate' => $birthDate,
            'mobileNumber' => (!empty($member->mobileNumber)) ? $member->mobileNumber : 'not set',
            'homeNumber' => (!empty($member->homeNumber)) ? $member->homeNumber : 'not set',
            'workNumber' => (!empty($member->workNumber)) ? $member->workNumber : 'not set',
            'altNumber' => (!empty($member->altNumber)) ? $member->altNumber : 'not set',
            'residentialAddress' => $member->residentialAddress,
            'surbub' => $member->surbub,
            'postalAddress' => $member->postalAddress,
            'addressMin' => (!empty($member->residentialAddress) && strlen($member->residentialAddress > 20)) ? substr($member->residentialAddress, 0, 20) . '...' : $member->residentialAddress,
            'postalCode' => $member->postalCode,
            'city' => $member->city,
            'believerInfo' => $member->believerInfo,
        ];
    }

    public function includeDepartments(Member $member) {
        return $this->collection($member->departments, new DepartmentMemberTransformer());
    }
    
    public function includeGroups(Member $member) {
        return $this->collection($member->groups, new GroupMemberTransformer());
    }

    public function includeFamilies(Member $member) {
        return $this->collection($member->families, new FamilyMemberTransformer());
    }
    
    public function includeQualifications(Member $member) {
        return $this->collection($member->qualifications, new QualificationTransformer());
    }
    
    public function includeSkills(Member $member) {
        return $this->collection($member->skills, new SkillTransformer());
    }

}
