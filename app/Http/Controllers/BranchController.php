<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\NullableFields;
use App\Http\Controllers\AppController;
use App\Branch;
use App\BranchService;
use App\Counter;
use App\Service;
use Session;

class BranchController extends Controller
{
    use NullableFields;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|alpha_dash|unique:branches|max:255',
            'name' => 'required|max:255',
            'desc' => 'max:255'
        ]);
        
        if ($validator->fails()) {

            return back()->withErrors($validator)->with('add_branch_error', 'fail')->withInput();
        }
        else {
            $branch = new Branch;

            $branch->code = $request->code;
            $branch->name = $request->name;
            $branch->desc = $this->nullIfEmpty($request->desc);
            
            $branch->save();
                    
            Session::flash('success', 'New branch created!');

            return redirect()->route('config.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $branch = Branch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|alpha_dash|max:255|unique:branches,code,'.$branch->id,
            'name' => 'required|max:255',
            'desc' => 'max:255'
        ]);

        // $validator = Validator::make($request->all(), [
        //     'code' => [
        //         'required', 'alpha_dash', 'max:255',
        //         Rule::unique('branches')->ignore($branch->id),
        //     ],
        //     'name' => 'required|max:255',
        //     'desc' => 'max:255'
        // ]);


        if ($validator->fails()) {

            return back()->withErrors($validator)->with("edit_branch_error", $id)->withInput();
        }
        else {
            $branch->code = $request->code;
            $branch->name = $request->name;
            $branch->desc = $this->nullIfEmpty($request->desc);
            
            $branch->save();
                    
            Session::flash('success', 'Branch updated!');

            return redirect()->route('config.index');

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
        $branch = Branch::findOrFail($id);

        $branch->delete();

        Session::flash('success', 'Branch is deleted!');

        return redirect()->route('config.index');
    }

    public function updateCounter(Request $request, $id) {

        $branch = Branch::findOrFail($id);

        $branch->counters()->sync($request->get('counters'));

        Session::flash('success', 'Branch counter updated!');

        return redirect()->route('config.index');
    }

    public function addService(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'service' => 'required',
            'default_wait_time_hr' => 'numeric|between:0,23',
            'default_wait_time_min' => 'numeric|between:0,59',
            'default_wait_time_sec' => 'numeric|between:0,59',
        ]);

        // return $request;
        if ($validator->fails()) {

            return back()->withErrors($validator)->with("add_branch_service_error", $id)->withInput();
        }
        else {
            $branch = Branch::findOrFail($id);

            $default_wait_time = AppController::timeToSec($request->default_wait_time_hr, $request->default_wait_time_min, $request->default_wait_time_sec);

            $branch->services()->attach($request->service, ['default_wait_time' => $default_wait_time]);

            Session::flash('success', 'Service added to branch!');

            return redirect()->route('config.index');
        }
    }

    public function updateService(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'service' => 'required',
            'default_wait_time_hr' => 'numeric|between:0,23',
            'default_wait_time_min' => 'numeric|between:0,59',
            'default_wait_time_sec' => 'numeric|between:0,59',

            'system_wait_time_hr' => 'numeric|between:0,23',
            'system_wait_time_min' => 'numeric|between:0,59',
            'system_wait_time_sec' => 'numeric|between:0,59',
        ]);

        // return $request;
        if ($validator->fails()) {

            return back()->withErrors($validator)->with("edit_branch_service_error", $id)->withInput();
        }
        else {
            $branchService = BranchService::findOrFail($id);

            $branch = Branch::findOrFail($branchService->branch_id);

            $default_wait_time = AppController::timeToSec($request->default_wait_time_hr, $request->default_wait_time_min, $request->default_wait_time_sec);
            $system_wait_time = AppController::timeToSec($request->system_wait_time_hr, $request->system_wait_time_min, $request->system_wait_time_sec);

            $branchService->default_wait_time = $default_wait_time;
            $branchService->system_wait_time = $system_wait_time;
            
            $branchService->save();

            Session::flash('success', 'Branch service updated!');

            return redirect()->route('config.index');
        }
    }

    public function deleteService(Request $request, $id) {

        $branchService = BranchService::findOrFail($id);

        $branch = Branch::findOrFail($branchService->branch_id);

        $branch->services()->detach($branchService->service_id);

        Session::flash('success', 'Service deleted from branch!');

        return redirect()->route('config.index');
    }
}
