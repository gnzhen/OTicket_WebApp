<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Traits\NullableFields;
use App\Branch;
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
        $input = ['modal_id' => '#addBranchModal',];

        $validator = Validator::make($request->all(), [
            'id' => 'required|alpha_dash|unique:branches|max:255',
            'name' => 'required|max:255',
            'desc' => 'max:255'
        ]);

        if ($validator->fails()) {

            $input['autoOpenModal'] = true;

            return back()->withErrors($validator)->with("add_branch_error", "Fail to add branch.")->withInput();
        }
        else {
            $branch = new Branch;

            $branch->id = $request->id;
            $branch->name = $request->name;
            $branch->desc = $this->nullIfEmpty($request->desc);
            
            $branch->save();
                    
            Session::flash('success', 'New branch created!');

            return redirect()->route('config.index');
        }


        // $validator->after(function ($validator) {
        //     if ($validator->fails()) {
        //         Session::flash('openModal', 'addBranch');
        //         return redirect()->route('config.index');
        //     }
        //     else{
        //         $branch = new Branch;

        //         $branch->id = $request->id;
        //         $branch->name = $request->name;
        //         $branch->desc = $this->nullIfEmpty($request->desc);
                
        //         $branch->save();
                        
        //         Session::flash('success', 'New branch created!');
        //     }
        // });
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $branches = Branch::select('id as branch_id', 'name', 'desc')->get();

        return response($branches);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
