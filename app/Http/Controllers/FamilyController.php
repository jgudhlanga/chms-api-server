<?php

namespace App\Http\Controllers;

use App\Models\Families\Family;
use App\Models\Families\FamilyMember;
use App\Transformers\FamilyTransformer;
use Illuminate\Http\Request;
use Auth;

class FamilyController extends Controller {

    /**
     * @return mixed
     */
    public function index() {
        $families = Family::all();
        return response()->collection($families, new FamilyTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rules = ['name' => ['required']];
        $payload = app('request')->only('name');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        } else {
            $params = $request->all();
            $params['createdBy'] = Auth::guard()->user()->id;
            $family = new Family($params);
            $family->save();
            if (isset($family->id)) {
                $family = Family::find($family->id);
                return response()->item($family, new FamilyTransformer());
            } else {
                return response()->serverError();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $family = Family::find($id);
        if (isset($family->id)) {
            return response()->item($family, new FamilyTransformer());
        } else {
            return response()->dataNotFound('The intended family was not found');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function update(Request $request) {
        $family = (isset($request->id) && $request->id > 0) ? Family::find($request->id) : [];
        if (isset($family->id)) {
            $rules = ['name' => ['required']];

            $payload = app('request')->only('name');

            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $params = $request->all();
                $family->update($params);
                return response()->message('Family Successfully updated');
            }
        } else {
            return response()->dataNotFound('The intended family was not found');
        }
    }

    public function destroy($familyId) {
        $family = Family::find($familyId);
        if (isset($family->id)) {
            $family->delete();
            return response()->message('The family was successfully deleted');
        } else {
            return response()->dataNotFound('The intended family was not found');
        }
    }

    public function activate($familyId, Request $request) {
        $family = ($familyId > 0) ? Family::find($familyId) : [];
        if (isset($family->id)) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $family->update(['isActive' => $request->activate]);
                //return an object with latest information
                return response()->item($family, new FamilyTransformer());
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended family was not found');
        }
    }

    public function storeMembers(Request $request) {
        $familyId = ($request->familyId > 0) ? $request->familyId : 0;
        $family = Family::find($familyId);
        if (isset($family->id)) {
            if ((isset($request->membership) && (count($request->membership) > 0))) {
                foreach ($request->membership as $memberId => $true) {
                    //check for duplication
                    $memberExist = FamilyMember::where('member_id', '=', $memberId)->get()->first();
                    if (isset($memberExist->id)) {
                        continue;
                    }
                    $familyMember = new FamilyMember(['member_id' => $memberId, 'createdBy' => Auth::guard()->user()->id]);
                    $family->addMember($familyMember);
                }

                return response()->item($family, new FamilyTransformer());
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended family was not found');
        }
    }

}
