<?php

namespace App\Http\Controllers;

use App\Models\CellGroups\CellGroup;
use App\Models\CellGroups\MemberGroup;
use App\Transformers\CellGroupTransformer;
use Illuminate\Http\Request;
use Auth;

class CellGroupController extends Controller {

    /**
     * @return mixed
     */
    public function index() {
        $groups = CellGroup::all();
        return response()->collection($groups, new CellGroupTransformer());
    }

    /**
     * @param $groupId
     * @return $this
     */
    public function getCellGroup($groupId) {
        $group = CellGroup::find($groupId);
        if (isset($group->id)) {
            return response()->item($group, new CellGroupTransformer());
        } else {
            return response()->dataNotFound('The intended cell group was not found');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array|void
     */
    public function saveCellGroup(Request $request) {
        $rules = ['name' => ['required']];
        $payload = app('request')->only('name');
        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        } else {
            $params = $request->all();
            /* convert string date to timestamp */
            if (isset($request->dateFormed) && $request->dateFormed != '') {
                $params['dateFormed'] = strtotime($request->dateFormed);
            }
            $params['createdBy'] = Auth::guard()->user()->id;
            $group = new CellGroup($params);
            $group->save();
            if (isset($group->id)) {
                $group = CellGroup::find($group->id);
                return response()->item($group, new CellGroupTransformer())->setStatusCode(200);
            } else {
                return response()->serverError();
            }
        }
    }

    public function updateCellGroup(Request $request, $id) {
        $group = ($id > 0) ? CellGroup::find($id) : [];
        if (isset($group->id)) {
            $rules = ['name' => ['required']];
            $payload = app('request')->only('name');
            $validator = app('validator')->make($payload, $rules);
            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $params = $request->all();
                /* convert string date to timestamp */
                if (isset($request->dateFormed) && $request->dateFormed != '') {
                    $params['dateFormed'] = strtotime($request->dateFormed);
                }
                $group->update($params);
                return response()->item($group, new CellGroupTransformer())->setStatusCode(200);
            }
        } else {
            return response()->dataNotFound('The intended cell group was not found');
        }
    }

    public function deleteCellGroup($groupId) {
        $group = CellGroup::find($groupId);
        if (isset($group->id)) {
            $group->delete();
            return response()->message('The cell group was successfully deleted');
        } else {
            return response()->dataNotFound('The intended cell group was not found');
        }
    }

    public function activateCellGroup($groupId, Request $request) {
        $group = ($groupId > 0) ? CellGroup::find($groupId) : [];
        if (isset($group->id)) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $group->update(['isActive' => $request->activate]);
                if ((int) $request->activate == 0) {
                    return response()->message('Cell Group successfully deactivated');
                } else {
                    return response()->message('Cell Group successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended cell group was not found');
        }
    }

    public function storeMembership(Request $request, $groupId) {
        $group = ($groupId > 0) ? CellGroup::find($groupId): [];
        if (isset($group->id)) {
            if ((isset($request->membership) && (count($request->membership) > 0))) {
                foreach ($request->membership as $memberId => $true) {
                    //check for duplication
                    $memberExist = MemberGroup::where('member_id', '=', $memberId)->get()->first();
                    if (isset($memberExist->id)) {
                        continue;
                    }
                    $groupMember = new MemberGroup(['member_id' => $memberId, 'createdBy' => Auth::guard()->user()->id]);
                    $group->addMember($groupMember);
                }
                return response()->item($group, new CellGroupTransformer())->setStatusCode(200);
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended cell group was not found');
        }
    }

    /**
     * 
     * @param int $id
     * @return array latest group details
     */

    public function deleteMembership($id) {
        $member = MemberGroup::find($id);
        
        if (isset($member->id)) {
            $member->delete();
            return response()->message('Member was successfully deleted');
        } else {
            return response()->dataNotFound('The intended Member was not found');
        }
    }

}
