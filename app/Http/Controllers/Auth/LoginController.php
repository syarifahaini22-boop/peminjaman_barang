<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Jika menggunakan email sebagai username
    protected $username = 'email';

    // Atau override method credentials()
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            // 'status' => 'active', // jika ada kolom status
        ];
    }

    // Atau jika ingin lebih sederhana
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Cek credentials manual
        $credentials = $request->only('email', 'password');
        
        // Tambahkan kondisi tambahan jika perlu
        // $credentials['status'] = 'active';
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
}