<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Invite;
use App\Mail\InviteCreated;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    //
    public function registered() 
    {
        $users = User::all();

        return view('admin.register')->with('users', $users );
    }

    public function createUser() 
    {
        return view('admin.register-create');

    }

    public function storeUser(Request $request)
    {
        User::create($request->except(['_token']));
        return redirect('/admin/role-register')->with('status', 'your data is saved');
    }


    public function registeredit(Request $request, $id) 
    {
        $users = User::findOrFail($id);

        return view('admin.register-edit')->with('users', $users );
    } 
    public function registerupdate(Request $request, User $user, $id) 
    {
        $users = User::find($id);
        $valid = $request->validate([
            'nome' => 'required | max:600',
            'usertype' => 'required'
        ]);

        $data = [
            'nome' => $request->nome,
            'usertype' => $request->usertype,
            'email' => $request->email,
            'password' => Hash::make($request->password) //*pt
        ];

        $query =  $users->update($data);
        
        return redirect('/admin/role-register')->with('status', 'your data is saved');

    }

    public function editPsw(User $user) 
    {
        return view('admin.passw.userpaswedit', compact('user'));
    } 

    public function updatePsw(User $user, Request $request ) 
    {
        User::where('id', $user->id)->update(['password' => $request->input('password')]);
        return redirect('/role-register')->with('status', 'your data is saved');
    }

    public function invite_view() {
        return view('admin.inviteutente');
    }

    public function process_invites(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (Invite::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'There exists an invite with this email!');
            }
        });
        if ($validator->fails()) {
            return redirect(route('invite_view'))
                ->withErrors($validator)
                ->withInput();
        }
        do {
    
            $token = Str::random();
        } 
        while (Invite::where('token', $token)->first());
        
        $invite = Invite::create([
            'email' => $request->get('email'),
            'token' => $token
        ]);
    
        Mail::to($request->get('email'))->send(new InviteCreated($invite));
    
        return redirect()
            ->back();
    }

    public function accept($token)
    {
    // Look up the invite
    if (!$invite = Invite::where('token', $token)->first()) {
        //if the invite doesn't exist do something more graceful than this
        abort(404);
    }
    // create the user with the details from the invite
    User::create(['email' => $invite->email]);
    // delete the invite so it can't be used again
    $invite->delete();
    // here you would probably log the user in and show them the dashboard, but we'll just prove it worked
    return 'Good job! Invite accepted!';
    }  

    public function registerdelete($id)
    {
        $users = User::findOrFail($id);

        $users->delete();

        return redirect('/admin/role-register')->with('status', 'your data is saved');
    }
}
