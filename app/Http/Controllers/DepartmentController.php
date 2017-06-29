<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transformers\DepartmentTransformer;
use App\Models\Departments\Department;
use Auth;

class DepartmentController extends Controller {

    /**
     *  get all the Departments
     * @return mixed
     */
    public function index() {
        $departments = Department::all();
        return response()->collection($departments, new DepartmentTransformer());
    }

    /**
     * get a department
     * @param id
     * @return this
     */
    public function getDepartment($id) {
        $department = Department::find($id);
        if (isset($department->id)) {
            return response()->item($department, new DepartmentTransformer());
        } else {
            return response()->dataNotFound('Department was not found');
        }
    }

    /**
     * @param request
     * @return this
     */
    public function storeDepartment(Request $request) {
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
            $department = new Department($params);
            $department->save();
            if (isset($department->id)) {
                $department = Department::find($department->id);
                return response()->item($department, new DepartmentTransformer())->setStatusCode(200);
            } else {
                return response()->serverError();
            }
        }
    }

    public function updateDepartment(Request $request) {
        $department = (isset($request->id) && $request->id > 0) ? Department::find($request->id) : [];
        if (isset($department->id)) {
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
                $department->update($params);
                return response()->message('Department Successfully updated');
            }
        } else {
            return response()->dataNotFound('Department was not found');
        }
    }

    public function deleteCellGroup($id) {
        $department = Department::find($id);
        if (isset($department->id)) {
            $department->delete();
            return response()->message('Department was successfully deleted');
        } else {
            return response()->dataNotFound('Department not found');
        }
    }

    public function activateDepartment($id, Request $request) {
        $department = ($id > 0) ? Department::find($id) : [];
        if (isset($department->id)) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $department->update(['isActive' => $request->activate]);
                if ((int) $request->activate == 0) {
                    return response()->message('Department successfully deactivated');
                } else {
                    return response()->message('Department successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('Department not found');
        }
    }

}
