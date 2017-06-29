<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Security\Role;
use Auth;
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = ['name' => 'required|unique:roles'];
        $payload = app('request')->only('name');
        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        }
        $params = $request->all();
        $role = new Role($params);
        $role->createdBy = Auth::guard()->user()->id;
        if($role->save()){
           $role = Role::find($role->id);
            return response()->json($role);
        }else{
            return response()->message('Something went wrong, check your data');
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if(isset($role->id)){
            return response()->json($role);
        }else{
            return response()->message('The intended role was not found');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = ['name' => 'required|unique'];
        $payload = app('request')->only('name');
        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        }
        $params = $request->all();
        $role = Role::find($id);
        if(isset($role->id)){
            $role->lastUpdatedBy = Auth::guard()->user()->id;
            if($role->update($params)){
               $role = Role::find($role->id);
                return response()->json($role);
            }else{
                return response()->message('Something went wrong, check your data');
            }  
        }else{
            return response('The intended role was not found');
        }             
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if(isset($role->id)){
            $role->delete();
            return response()->message('Role successfully deleted');
        }else{
            return response()->message('The intended role was not found')->setStatusCode(300);
        }
    }
    
     public function activate($id, Request $request) {
        $role = ($id > 0) ? Role::find($id) : [];
        if (isset($role->id)) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $role->update(['isActive' => $request->activate]);
                if ((int) $request->activate == 0) {
                    return response()->message('role successfully deactivated');
                } else {
                    return response()->message('role successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The role was not found');
        }
    }
}
