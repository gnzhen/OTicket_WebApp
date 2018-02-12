<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\NullableFields;
use App\Http\Controllers\AppController;
use App\Branch;
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

            Session::flash('add_branch_error', 'Fail to add branch.');

            return back()->withErrors($validator)->withInput();
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
            'code' => ['required', 'alpha_dash', 'max:255',
                Rule::unique('branches')->ignore($branch->id),
            ],
            'name' => 'required|max:255',
            'desc' => 'max:255'
        ]);

        if ($validator->fails()) {
        
            Session::flash('edit_branch_error', 'Fail to edit branch.');

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

        //delete branchService and branchCounter

        Session::flash('success', 'Branch is deleted!');

        return redirect()->route('config.index');
    }

    public function updateCounter(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'counters' => 'required',
        ]);

        if ($validator->fails()) {

            Session::flash('edit_branch_counter_error', 'Fail to edit counters from branch.');

            return back()->withErrors($validator)->withInput();
        }
        else {

            $branch = Branch::findOrFail($id);

            $branch->counters()->sync($request->get('counters'));

            Session::flash('success', 'Counters added to branch!');

            return redirect()->route('config.index');
        }
    }

    public function addService(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'service' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('add_branch_service_error', 'Fail to add service from branch.');

            return back()->withErrors($validator)->withInput();
        }
        else {
            $branch = Branch::findOrFail($id);

            $branch->services()->attach($request->service, ['default_wait_time' => '1150']);

            Session::flash('success', 'Service added to branch!');

            return redirect()->route('config.index');
        }
    }

    public function deleteService(Request $request, $id) {

        $branch = Branch::findOrFail($id);

        $branch->services()->detach($request->service_id);

        return redirect()->route('config.index');
    }
}
