<?php

namespace App\Transformers;

use App\Models\Members\Member;
use App\Models\Members\Skill;
use League\Fractal\TransformerAbstract;

class SkillTransformer extends TransformerAbstract {

    /**
     * 
     * @param Skill $skill
     * @return type
     */
    public function transform(Skill $skill) {
        //get the member names
        $member = Member::find($skill->member_id);
        return [
            'id' => $skill->id,
            'name' => $skill->name,
            'description' => $skill->description,
            'fieldId' => $skill->field_id,
            'memberId' => $skill->member_id,
            'createdBy' => $skill->createdBy,
            'memberName' => (isset($member->firstName)) ? $member->firstName : 'not set',
            'memberSurname' => (isset($member->surname)) ? $member->surname : 'not set',
            'memberInitial' => (isset($member->firstName)) ? substr($member->firstName, 0, 1) : '',
        ];
    }
}
