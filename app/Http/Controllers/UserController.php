<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use App\Traits\NullableFields;
use Illuminate\Validation\Rule;
use App\User;
use App\Branch;
use App\Role;
use Session;

class UserController extends Controller
{
    use RegistersUsers;
    use NullableFields;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('username')->get();
        $branches = Branch::get();
        $roles = Role::get();

        return view('user')->withUsers($users)->withBranches($branches);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'role_id' => $data['role'],
            'branch_id' => $this->nullIfEmpty($data['branch']),
            'password' => bcrypt($data['password']),
        ]);
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
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|integer',
            'branch' => 'string|max:255|nullable',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {

            $error = "";

            if($request->role == '3'){
                $error = 'register_staff_error';
            }
            else if($request->role == '2'){
                $error = 'register_admin_error';
            }
            else if($request->role == '1'){
                $error = 'register_super_admin_error';
            }
            else{
                Session::flash('fail', 'Fail to register user.');
            }


            return back()->withErrors($validator)->with($error, 'fail')->withInput();
        }
        else {
            event(new Registered($user = $this->create($request->all())));

            Session::flash('success', 'A new user is added!');

            return $this->registered($request, $user)?: redirect()->route('user.index');
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
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'branch' => 'string|max:255|nullable'
        ]);

        if ($validator->fails()) {

            $error = "";

            if($request->role == '3'){
                $error = 'update_staff_error';
            }
            else if($request->role == '2'){
                $error = 'update_admin_error';
            }
            else if($request->role == '1'){
                $error = 'update_super_admin_error';
            }
            else{
                Session::flash('fail', 'Fail to register user.');
            }

            return back()->withErrors($validator)->with($error, 'fail')->withInput();
        }
        else {
            $user->username = $request->username;
            $user->email = $request->email;
            $user->branch_id = $this->nullIfEmpty($request->branch);
            
            $user->save();
                    
            Session::flash('success', 'User updated!');

            return redirect()->route('user.index');

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
        $user = User::findOrFail($id);

        $user->delete();

        Session::flash('success', 'User is deleted!');

        return redirect()->route('user.index');
    }
}
