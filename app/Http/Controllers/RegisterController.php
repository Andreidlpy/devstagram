<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function index()
    {
        if( auth()->user() ){
            return redirect()->route('posts.index', auth()->user()->username );
        } else {
            return view('auth.register');
        }
    }
    
    public function store(Request $request)
    {
        // dd($request->get('name'));
        $request->request->add(['username' => Str::slug($request->username)]);
        // //Validacion
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request-> name,
            'username' => $request-> username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // //Autenticar al usuario
        auth()->attempt([
            'email'=> $request->email,
            'password'=> $request->password,
        ]);

        // //Otra forma de autenticar
        // auth()->attempt($request->only('email', 'password'));

        // //redereccionar
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
