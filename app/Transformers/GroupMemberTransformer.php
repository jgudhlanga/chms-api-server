<?php

namespace App\Transformers;

use App\Models\Members\Member;
use App\Models\CellGroups\GroupMember;
use App\Models\CellGroups\CellGroup;
use League\Fractal\TransformerAbstract;

class GroupMemberTransformer extends TransformerAbstract {

    
    public function transform(GroupMember $groupMember) {
        //get the member names
        $member = Member::find($groupMember->member_id);
        $group = CellGroup::find($groupMember->cell_group_id);
        return [
            'id' => $groupMember->id,
            'cellGroupId' => $groupMember->cell_group_id,
            'memberId' => $groupMember->member_id,
            'roleId' => $groupMember->roleId,
            'createdBy' => $groupMember->createdBy,
            'memberName' => (isset($member->firstName)) ? $member->firstName : 'not set',
            'memberSurname' => (isset($member->surname)) ? $member->surname : 'not set',
            'memberInitial' => (isset($member->firstName)) ? substr($member->firstName, 0, 1) : '',
            'groupName' => (isset($group->name)) ? $group->name : '',
        ];
    }
}
