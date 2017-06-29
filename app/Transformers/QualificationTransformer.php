<?php

namespace App\Transformers;

use App\Models\Members\Member;
use App\Models\Members\Qualification;
use League\Fractal\TransformerAbstract;

class QualificationTransformer extends TransformerAbstract {

    /**
     * 
     * @param Qualification $qualification
     * @return type
     */
    public function transform(Qualification $qualification) {
        //get the member names
        $member = Member::find($qualification->member_id);
        return [
            'id' => $qualification->id,
            'name' => $qualification->name,
            'subjects' => $qualification->subjects,
            'institution' => $qualification->institution,
            'yearObtained' => $qualification->yearObtained,
            'qualificationLevel' => $qualification->qualificationLevel,
            'memberId' => $qualification->member_id,
            'createdBy' => $qualification->createdBy,
            'memberName' => (isset($member->firstName)) ? $member->firstName : 'not set',
            'memberSurname' => (isset($member->surname)) ? $member->surname : 'not set',
            'memberInitial' => (isset($member->firstName)) ? substr($member->firstName, 0, 1) : '',
        ];
    }
}
