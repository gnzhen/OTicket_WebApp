<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AppController;
use App\Counter;
use Session;

class CounterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
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
            'code' => 'required|alpha_dash|unique:counters|max:255',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            return back()->withErrors($validator)->with('add_counter_error', 'fail')->withInput();
        }
        else {
            $counter = new Counter;

            $counter->code = $request->code;
            $counter->name = $request->name;
            
            $counter->save();
                    
            Session::flash('success', 'New counter created!');

            return redirect()->route('config.index');
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
        $counter = Counter::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'alpha_dash', 'max:255',
                Rule::unique('counters')->ignore($counter->id),
            ],
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
        
            Session::flash('fail', 'Update Fail! Something wrong.');

            return back()->withErrors($validator)->with("edit_counter_error", $id)->withInput();
        }
        else {
            $counter->code = $request->code;
            $counter->name = $request->name;
            
            $counter->save();
                    
            Session::flash('success', 'Counter updated!');

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
        $counter = Counter::findOrFail($id);

        $counter->delete();

        //delete counterService and counterCounter

        Session::flash('success', 'Counter is deleted!');

        return redirect()->route('config.index');
    }
}
