<?php

namespace App\Http\Controllers;

use App\Transformers\ModuleTransformer;
use App\Models\Modules\Module;
use App\Models\Modules\Page;
use App\Transformers\PageTransformer;
use Illuminate\Http\Request;

class ModulesController extends Controller {

    /** get all the modules
     * @return mixed
     */
    public function index() {
        $modules = Module::all();
        return response()->collection($modules, new ModuleTransformer());
    }

    /*     * Get a module
     * @param $moduleId
     * @return $this module object
     */

    public function getModule($moduleId) {
        $module = Module::find($moduleId);
        if (isset($module->id) && $module->id > 0) {
            return response()->item($module, new ModuleTransformer());
        } else {
            return response()->dataNotFound();
        }
    }

    /** Assuming that the front end has validated the data
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function createModule(Request $request) {
        $rules = [
            'title' => ['required'], 'path' => ['required']
        ];

        $payload = app('request')->only('title', 'path');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        } else {
            $module = new Module($request->all());
            $module->save();
            if (isset($module->id)) {
                $module = Module::find($module->id);
                return response()->item($module, new ModuleTransformer());
            } else {
                return response()->serverError('Something went wrong');
            }
        }
    }

    public function updateModule(Request $request) {
        $rules = [
            'name' => ['required'], 'title' => ['required'], 'path' => ['required']
        ];
        $module = (isset($request->id)) ? Module::find($request->id) : [];
        if (isset($module->id) && $module->id > 0) {
            $payload = app('request')->only('name', 'title', 'path');
            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $module->update($request->all());
                return response()->message('Module successfully update');
            }
        } else {
            return response()->dataNotFound();
        }
    }

    public function deleteModule($moduleId) {
        $module = Module::find($moduleId);
        if (isset($module->id) && $module->id > 0) {
            $module->delete();
            return response()->message('The module was successfully deleted');
        } else {
            return response()->dataNotFound('The intended module was not found');
        }
    }

    public function activateModule($moduleId, Request $request) {
        $module = ($moduleId > 0) ? Module::find($moduleId) : [];
        if (isset($module->id)) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $module->update(['isActive' => $request->activate]);
                if ((int) $request->activate == 0) {
                    return response()->message('Module successfully deactivated');
                } else {
                    return response()->message('Module successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended module was not found');
        }
    }

    /**
     * @param $moduleId
     * @param $request
     * @return array|void
     */
    public function orderModule($moduleId, Request $request) {
        //get the current
        $module = Module::find($moduleId);
        $currentPosition = $module->position;
        if (isset($module->id)) {
            if (strtolower($request->direction) == 'up' || strtolower($request->direction) == 'down') {
                $next = [];
                if (strtolower($request->direction) == 'up')
                    $next = Module::where('position', $module->position - 1)->first();
                else if (strtolower($request->direction) == 'down')
                    $next = Module::where('position', $module->position + 1)->first();
                if (isset($next->id) && $next->id > 0) {
                    //update the current module
                    $module->update(['position' => $next->position]);
                    //update the next module
                    $next->update(['position' => $currentPosition]);
                }
                /* maintain smooth order with not gaps */
                $allModules = Module::orderBy('position')->get();
                $count = 1;
                foreach ($allModules as $row) {
                    $row->update(['position' => $count]);
                    $count++;
                }
                return response()->message('Module successfully ordered');
            } else
                return response()->badRequest();
        } else
            return response()->dataNotFound('The intended module was not found');
    }

    /**
     * @param $pageId
     * @return $this|void
     */
    public function getPage($pageId) {
        $page = Page::find($pageId);
        if (isset($page->id) && $page->id > 0)
            return response()->item($page, new PageTransformer());
        else
            return response()->dataNotFound('You search was not successful: Nothing found');
    }

    /** Assuming that the front end has validated the data
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function createPage(Request $request) {
        $module = (isset($request->moduleId)) ? Module::find($request->moduleId) : [];
        if (isset($module->id) && $module->id > 0) {
            $rules = [
                'title' => ['required'], 'path' => ['required']
            ];

            $payload = app('request')->only('title', 'path');

            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $page = new Page($request->all());
                $module->addPage($page);
                return response()->message('The page was successfully created');
            }
        } else {
            return response()->dataNotFound('The page you are intending to create does not belong to a module');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array|void
     */
    public function updatePage(Request $request) {
        $rules = [
            'title' => ['required'], 'path' => ['required']
        ];
        $page = (isset($request->id)) ? Page::find($request->id) : [];
        if (isset($page->id) && $page->id > 0) {
            $payload = app('request')->only('title', 'path');
            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $page->update($request->all());
                return response()->message('Page successfully update');
            }
        } else {
            return response()->dataNotFound();
        }
    }

    public function deletePage($pageId) {
        $page = Page::find($pageId);
        if (isset($page->id) && $page->id > 0) {
            $page->delete();
            return response()->message('The page was successfully deleted');
        } else {
            return response()->dataNotFound('The page was not found');
        }
    }

    public function activatePage($pageId, Request $request) {
        $page = ($pageId > 0) ? Page::find($pageId) : [];
        if (isset($page->id) && $page->id > 0) {
            if ((int) $request->activate == 0 || (int) $request->activate == 1) {
                $page->update(['isActive' => $request->activate]);
                if ($request->activate == 0) {
                    return response()->message('Page successfully deactivated');
                } else {
                    return response()->message('Page successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            response()->dataNotFound('The page was not found');
        }
    }

    /**
     * @param $pageId
     * @param $direction
     * @return array|void
     */
    public function orderPage($pageId, $direction) {
        //get the current
        $page = Page::find($pageId);
        $currentPosition = $page->position;
        if (isset($page->id)) {
            if (strtolower($direction) == 'up' || strtolower($direction) == 'down') {
                $next = [];
                if (strtolower($direction) == 'up') {
                    $next = Page::where('position', $page->position - 1)->first();
                } else if (strtolower($direction) == 'down') {
                    $next = Page::where('position', $page->position + 1)->first();
                }
                if (isset($next->id) && $next->id > 0) {
                    //update the current page
                    $page->update(['position' => $next->position]);
                    //update the next page
                    $next->update(['position' => $currentPosition]);
                }
                /* maintain smooth order with not gaps */
                $allPages = Page::orderBy('position')->get();
                $count = 1;
                foreach ($allPages as $row) {
                    $row->update(['position' => $count]);
                    $count++;
                }
                return response()->message('Page successfully ordered');
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended page was not found');
        }
    }

}
