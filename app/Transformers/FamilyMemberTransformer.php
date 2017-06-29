<?php

namespace App\Transformers;

use App\Models\Members\Member;
use App\Models\Families\FamilyMember;
use App\Models\Families\Family;
use League\Fractal\TransformerAbstract;

class FamilyMemberTransformer extends TransformerAbstract {

    /**
     * @param \App\Models\Families\MemberFamily $familyMember
     * @return array
     */
    public function transform(FamilyMember $familyMember) {
        //get the member names
        $member = Member::find($familyMember->member_id);
        $family = Family::find($familyMember->family_id);
        return [
            'id' => $familyMember->id,
            'familyId' => $familyMember->family_id,
            'memberId' => $familyMember->member_id,
            'roleId' => $familyMember->roleId,
            'createdBy' => $familyMember->createdBy,
            'memberName' => (isset($member->firstName)) ? $member->firstName : 'not set',
            'memberSurname' => (isset($member->surname)) ? $member->surname : 'not set',
            'memberInitial' => (isset($member->firstName)) ? substr($member->firstName, 0, 1) : '',
            'familyName' => (isset($family->name)) ? $family->name : '',
        ];
    }
}
