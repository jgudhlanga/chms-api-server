<?php

namespace App\Http\Controllers;

use App\Models\Members\Member;
use App\Models\Members\Qualification;
use App\Models\Members\Skill;
use App\Models\Members\Believer;
use App\Transformers\MemberTransformer;
use App\Transformers\QualificationTransformer;
use App\Transformers\SkillTransformer;
use Illuminate\Http\Request;
use Auth;

class MembersController extends Controller {

    /** get all the members
     * @return mixed
     */
    public function index() {
        $members = Member::all();
        return response()->collection($members, new MemberTransformer());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function createMember(Request $request) {

        $rules = [
            'firstName' => 'required', 'surname' => 'required', 'mobileNumber' => 'required'
        ];

        $payload = app('request')->only('firstName', 'surname', 'mobileNumber');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        } else {

            $params = $request->all();
            /* convert string date to timestamp */
            if (isset($request->birthDate) && $request->birthDate != '') {
                $params['birthDate'] = strtotime($request->birthDate);
            }

            $member = new Member($params);
            $member->save();
            if (isset($member->id)) {
                $member = Member::find($member->id);
                return response()->item($member, new MemberTransformer());
            } else {
                return response()->serverError();
            }
        }
    }

    /**
     * 
     * @param type $memberId
     * @return Member member
     */
    public function getMember($memberId) {
        $member = Member::find($memberId);
        if (isset($member->id)) {
            $member = Member::find($member->id);
            return response()->item($member, new MemberTransformer());
        } else {
            return response()->dataNotFound();
        }
    }

    /**
     * 
     * @param Request $request
     * @return String confirmation message
     */
    public function updateMember(Request $request, $id) {

        $member = ($id > 0) ? Member::find($id) : [];
        if (isset($member->id)) {
            $rules = [
                'firstName' => 'required', 'surname' => 'required', 'mobileNumber' => 'required'
            ];

            $payload = app('request')->only('firstName', 'surname', 'mobileNumber');

            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $params = $request->all();
                /* convert string date to timestamp */
                if (isset($request->birthDate) && $request->birthDate != '') {
                    $params['birthDate'] = strtotime($request->birthDate);
                }
                $member->update($params);
                return response()->message('Member profile Successfully updated');
            }
        } else {
            return response()->dataNotFound('The intended member was not found');
        }
    }

    public function storeBeliever(Request $request, $memberId){
        //check if the member has been already in the believers table else insert
        $believer = Believer::where('member_id', $memberId)->first();
        $params = $request->all();
        $params['member_id'] = $memberId;
        if(isset($believer->id)){
            $believer->update($params);
        }else{
            $params['createdBy'] = Auth::guard()->user()->id;
            $believer = new Believer($params);
            $believer->save();
        }
        $member = Member::find($memberId);
        return response()->item($member, new MemberTransformer());
    }

    public function storePicture(Request $request, $id){
        
        $member = ($id > 0) ? Member::find($id) : [];
        if(isset($member->id))
        {
            $file = $request->file('file');
            //return $request;
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $return = $filename;        
            $request->file('file')->move(public_path('avatars'), $filename);
            return $filename;
            
        }else{
            return response()->dataNotFound('The intended member was not found');
        }
    }

    /**
     * 
     * @param Request $request
     * @return array, The last created qualification
     */
    public function createQualification(Request $request, $memberId) {
        $member = ($memberId > 0) ? Member::find($memberId) : [];
        if (isset($member->id) && $member->id > 0) {
            $rules = ['name' => ['required']];
            $payload = app('request')->only('name');

            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $qualification = new Qualification($request->all());
                $member->addQualification($qualification);
                if (isset($qualification->id)) {
                    return response()->item($qualification, new QualificationTransformer());
                } else {
                    return response()->serverError();
                }
            }
        } else {
            return response()->dataNotFound('The member does not exist');
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return String confirmation message
     */
    public function updateQualification(Request $request, $id) {
        $qualication = ($id > 0) ? Qualification::find($id) : [];
        if (isset($qualication->id) && $qualication->id > 0) {
            $rules = ['name' => 'required'];
            $payload = app('request')->only('name');
            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $qualication->update($request->all());
                return response()->message('Qualification successfully updated');
            }
        } else {
            return response()->dataNotFound();
        }
    }

    /**
     * 
     * @param type $id
     * @return String confirmation message
     */
    public function deleteQualification($id) {
        $qualification = Qualification::find($id);
        if (isset($qualification->id) && $qualification->id > 0) {
            $qualification->delete();
            return response()->message('The qualification was successfully deleted');
        } else {
            return response()->dataNotFound('The qualification was not found');
        }
    }

    /**
     * 
     * @param Request $request
     * @return Array last inserted
     */
    public function createSkill(Request $request, $memberId) {
        $member = ($memberId > 0) ? Member::find($memberId) : [];
        if (isset($member->id) && $member->id > 0) {
            $rules = ['name' => ['required']];
            $payload = app('request')->only('name');

            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $skill = new Skill($request->all());
                $member->addSkill($skill);
                if (isset($skill->id)) {
                    return response()->item($skill, new SkillTransformer());
                } else {
                    return response()->serverError();
                }
            }
        } else {
            return response()->dataNotFound('The member does not exist');
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return String confirmation message
     */
    public function updateSkill(Request $request, $id) {
        $skill = ($id > 0) ? Skill::find($id) : [];
        if (isset($skill->id) && $skill->id > 0) {
            $rules = ['name' => ['required']];
            $payload = app('request')->only('name');
            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $skill->update($request->all());
                return response()->message('Skill successfully updated');
            }
        } else {
            return response()->dataNotFound();
        }
    }

    /**
     * 
     * @param type $id
     * @return String confirmation message
     */
    public function deleteSkill($id) {
        $skill = Skill::find($id);
        if (isset($skill->id)) {
            $skill->delete();
            return response()->message('The skill was successfully deleted');
        } else {
            return response()->dataNotFound('The skill was not found');
        }
    }

}
