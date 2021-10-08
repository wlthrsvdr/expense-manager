<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{User, Roles};
use DB;
use Str;

class UserManagement extends Controller
{

    protected $data;
    protected $per_page;


    public function __construct()
    {
        $this->data['js'] = "UserManagement.js";
        $this->middleware('system.guest', ['except' => "logout"]);
    }

    public function users(Request $request)
    {

        $this->data['keyword'] = Str::lower($request->get('keyword'));

        $this->data['users'] = User::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query->whereRaw("name LIKE  UPPER('{$this->data['keyword']}%')")
                    ->orWhereRaw("LOWER(email)  LIKE  '{$this->data['keyword']}%'");
            }
        })->orderBy('created_at', "DESC")
            ->paginate($this->per_page);

        return view('admin.pages.users', $this->data);
    }

    public function store(Request $request)
    {

        $validator =  $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'user_role' => 'required'
        ]);

        if ($request->get('password') != $request->get('confirm_password')) {
            session()->flash('notification-status', "error");
            session()->flash('notification-msg', "Password not match.");

            goto callback;
        }

        DB::beginTransaction();
        try {

            $user = new User;
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->user_role = $request->get('user_role');
            $user->save();
            DB::commit();


            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Register successfully.");
            return redirect()->route('admin.users');
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
            return redirect()->back();
        }

        callback:
        session()->flash('notification-status', "failed");
        return redirect()->back();
    }

    public function get_user($id)
    {
        $data  = User::where('id', $id)->first();

        return  $data;
    }



    public function update(Request $request)
    {

        $validator =  $request->validate([
            'user_role' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = User::where('id', request('users_id'))->first();
            $user->user_role =  request('user_role');
            $user->email = request('email');
            $user->name = request('name');
            $user->save();
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Update successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return   $user;
    }

    public function delete_user($id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);

            $user->delete();



            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "User Deleted Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return   $user;
    }

    public function goto_roles(Request $request)
    {

        $this->data['keyword'] = Str::lower($request->get('keyword'));

        $this->data['roles'] = Roles::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query->whereRaw("role LIKE  UPPER('{$this->data['keyword']}%')")
                    ->orWhereRaw("LOWER(description)  LIKE  '{$this->data['keyword']}%'");
            }
        })->orderBy('created_at', "DESC")
            ->paginate($this->per_page);

        return view('admin.pages.role', $this->data);
    }

    public function store_roles(Request $request)
    {

        $validator =  $request->validate([
            'role' => 'required',
            'description' => 'required'
        ]);



        DB::beginTransaction();
        try {

            $role = new Roles;
            $role->role = strtolower($request->get('role'));
            $role->description = $request->get('description');
            $role->save();
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Role ADDED successfully.");
            return redirect()->route('admin.goto_roles');
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
            return redirect()->back();
        }

        callback:
        session()->flash('notification-status', "failed");
        return redirect()->back();
    }

    public function get_role($id)
    {
        $data  = Roles::where('id', $id)->first();

        return  $data;
    }

    public function get_user_role()
    {
        $data  = Roles::all();

        return  $data;
    }

    public function update_role(Request $request)
    {

        $validator =  $request->validate([
            'role' => 'required',
            'description' => 'required'
        ]);


        DB::beginTransaction();

        try {
            $role = Roles::where('id', request('role_id'))->first();
            $role->role =  strtolower(request('role'));
            $role->description = request('description');
            $role->save();
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Update Role Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return $role;
    }

    public function delete_role($id)
    {
        DB::beginTransaction();

        try {
            $role = Roles::find($id);

            $role->delete();



            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Role Deleted Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return   $role;
    }
}
