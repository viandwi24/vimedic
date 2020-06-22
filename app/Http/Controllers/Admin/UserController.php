<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $users = User::query();
            return DataTables::eloquent($users)
                ->addColumn('action', function (User $user) {
                    $user_json = "onclick='vm.editModal(" .
                        json_encode($user).
                        ")'";
                    return '
                        <div class="text-center">
                            <button '.$user_json.' type="button" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form method="post" action="'. route('admin.user.destroy', [$user->id]) .'" style="display:inline;">'.csrf_field().method_field('delete').'<button class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                        </dviv>
                    ';
                })
                ->make();
        }

        return view('pages.admin.user');
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
        $request->validate([
            'name' => 'required|string|min:3',
            'role' => 'required|string|in:admin,employee,doctor',
            'username' => 'required|string|min:3|unique:users,username',
            'password' => 'required|string|min:3',
        ]);
        $user = $request->only('name', 'role', 'username');
        $user['password'] = Hash::make($request->password);
        $store = User::create($user);
        return redirect()->route('admin.user.index')->with('alert', ['type' => 'success', 'text' => 'Save data successfully.']);
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
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'role' => 'required|string|in:admin,employee,doctor',
        ]);
        $newUser = $request->only('name', 'role');
        
        // detect password change or not
        if ($request->has('password') && $request->password != null)
        {
            $request->validate(['password' => 'required|string|min:3']);
            $user['password'] = Hash::make($request->password);
        }
        
        // detect username change or not
        if (
            $request->has('username') && 
            $request->username != null &&
            $request->username != $user->username
        )
        {
            $request->validate(['username' => 'required|string|min:3|unique:users,username']);
            $user['username'] = $request->username;
        }

        $update = $user->update($newUser);
        return redirect()->route('admin.user.index')->with('alert', ['type' => 'success', 'text' => 'Update data successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $delete = $user->delete();
        return redirect()->route('admin.user.index')->with('alert', ['type' => 'success', 'text' => 'Delete data successfully.']);
    }
}
