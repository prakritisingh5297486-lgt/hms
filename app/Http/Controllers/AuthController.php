<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'super-admin') {
            return redirect()->route('super-admin.dashboard');
        }
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            if ($user->role == 'super-admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('super-admin.dashboard'));
            }
            Auth::logout();
        }

        return redirect()->back()->withErrors(['email' => 'This credentials do not match our records'])->onlyInput('email');   //or  Authentication failed
    }
    //Login for Doctor

    public function doctorLogin(Request $request)
    {
        if (Auth::check() && AUth::user()->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role == 'doctor') {
                $request->session()->regenerate();
                return redirect()->intended(route('doctor.dashboard'));
            }
            Auth::Logout();
        }
        return back()->withErrors([
            'email' => 'This credentials do not match our records'
        ])->onlyInput('email');
    }
    public function doctorRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'department' => 'required',
            'license' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'doctor'
        ]);
        $user->doctor()->create([
            'department' => $data['department'],
            'license_id' => $data['license']
        ]);
        return redirect()->route('doctor.login')
            ->with('success', 'Registration successful. Please login.');
        // Auth::login($user);
        // return redirect()->route('doctor.login');
    }
    public function patientRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'blood_group' => 'required',
            'disease' => 'required|string|max:255',
            'number' => 'required|digits:10',
            'address' => 'required|string|max:500',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $imageName = null;
        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('patients'), $imageName);
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'patient',
            'image' => $imageName  
        ]);
        $user->patient()->create([
            'age' => $data['age'],
            'gender' => $data['gender'],
            'blood_group' => $data['blood_group'],
            'disease' => $request->disease,
            'number'      => $request->number,
            'address'     => $request->address,
            'user_id' => $user->id,          
        ]);
        return redirect()->route('patient.login')
            ->with('success', 'Registration successful. Please login.');
        // Auth::login($user);
        // return redirect()->route('patient.login');
    }
    //Patient Login
    public function patientLogin(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'patient') {
            return redirect()->route('patient.dashboard');
        }
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role == 'patient') {
                $request->session()->regenerate();
                return redirect()->intended(route('patient.dashboard'));
                //          $user = Auth::user();
                // return view('patient.dashboard', compact('user'));
            }
            Auth::Logout();
        }
        return back()->withErrors([
            'email' => 'This credentials do not match our records'
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
