<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class HomeController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        if ($role == '1') {
            return view('departemen.fgwh');
        }
        if ($role == '2') {
            return view('departemen.office');
        }
        if ($role == '3') {
            return view('departemen.maintenance');
        } else {
            return view('dashboard');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'numeric', 'in:0,1,2'], // Ensure role is one of the specified values
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

}
