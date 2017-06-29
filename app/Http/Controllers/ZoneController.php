<?php

namespace App\Http\Controllers;

use App\Models\Zones\Zone;
use App\Models\Zones\ZoneGroup;
use App\Transformers\ZoneTransformer;
use Illuminate\Http\Request;
use Auth;

class ZoneController extends Controller {

    /**
     * @return mixed
     */
    public function index() {
        $zones = Zone::all();
        return response()->collection($zones, new ZoneTransformer());
    }

    /**
     * @param $zoneId
     * @return $this
     */
    public function getZone($zoneId) {
        $zone = Zone::find($zoneId);
        if (isset($zone->id)) {
            return response()->item($zone, new ZoneTransformer());
        } else {
            return response()->dataNotFound('The intended zone was not found');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array|void
     */
    public function saveZone(Request $request) {
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
            $zone = new Zone($params);
            $zone->save();
            if (isset($zone->id)) {
                $zone = Zone::find($zone->id);
                return response()->item($zone, new ZoneTransformer())->setStatusCode(200);
            } else {
                return response()->serverError();
            }
        }
    }

    public function updateZone(Request $request) {
        $zone = (isset($request->id) && $request->id > 0) ? Zone::find($request->id) : [];
        if (isset($zone->id)) {
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
                $zone->update($params);
                return response()->message('Zone Successfully updated');
            }
        } else {
            return response()->dataNotFound('The intended Zone was not found');
        }
    }

    public function deleteZone($zoneId) {
        $zone = Zone::find($zoneId);
        if (isset($zone->id)) {
            $zone->delete();
            return response()->message('The Zone was successfully deleted');
        } else {
            return response()->dataNotFound('The intended zone was not found');
        }
    }

    public function activateZone($zoneId, Request $request) {
        $zone = ($zoneId > 0) ? Zone::find($zoneId) : [];
        if (isset($zone->id)) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $zone->update(['isActive' => $request->activate]);
                if ((int) $request->activate == 0) {
                    return response()->message('Zone successfully deactivated');
                } else {
                    return response()->message('Zone successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended zone was not found');
        }
    }

    public function storeGroups(Request $request) {
        $zoneId = ($request->zoneId > 0) ? $request->zoneId : 0;
        $zone = Zone::find($zoneId);
        if (isset($zone->id)) {
            if ((isset($request->groups) && (count($request->groups) > 0))) {
                foreach ($request->groups as $groupId => $true) {
                    //check for duplication
                    $groupExist = MemberGroup::where('cell_group_id', '=', $groupId)->get()->first();
                    if (isset($memberExist->id)) {
                        continue;
                    }
                    $zoneGroup = new ZoneGroup(['cell_group_id' => $groupId, 'createdBy' => Auth::guard()->user()->id]);
                    $zone->addGroup($zoneGroup);
                }
                return response()->item($zone, new ZoneTransformer())->setStatusCode(200);
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended zone was not found');
        }
    }
}
