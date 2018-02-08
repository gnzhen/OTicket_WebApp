<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AppController;
use App\Service;
use Session;

class ServiceController extends Controller
{
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
            'code' => 'required|alpha_dash|unique:services|max:255',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            Session::flash('add_service_error', 'Fail to add service.');

            return back()->withErrors($validator)->withInput();
        }
        else {
            $service = new Service;

            $service->code = $request->code;
            $service->name = $request->name;
            
            $service->save();
                    
            Session::flash('success', 'New service created!');

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
        $service = Service::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'alpha_dash', 'max:255',
                Rule::unique('services')->ignore($service->id),
            ],
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
        
            Session::flash('fail', 'Update Fail! Something wrong.');

            return back()->withErrors($validator)->with("edit_service_error", $id)->withInput();
        }
        else {
            $service->code = $request->code;
            $service->name = $request->name;
            
            $service->save();
                    
            Session::flash('success', 'Service updated!');

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
        $service = Service::findOrFail($id);

        $service->delete();

        //delete serviceService and serviceCounter

        Session::flash('success', 'Service is deleted!');

        return redirect()->route('config.index');
    }
}
