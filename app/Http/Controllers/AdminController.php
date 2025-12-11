<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'staff', 'supervisor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,staff,supervisor',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // TAMBAHKAN INI - verifikasi otomatis
        ]);

        return redirect()->route('admin.manage')->with('success', 'Admin berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        \Log::info('Update Request:', $request->all()); // Log data
        \Log::info('Admin ID:', ['id' => $id]);

        $admin = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,staff,supervisor',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation Failed:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // OTOMATIS VERIFIKASI saat update
        if ($admin->email !== $request->email) {
            $data['email_verified_at'] = now();
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        \Log::info('Admin Updated Successfully:', $data);

        return redirect()->route('admin.manage')->with('success', 'Admin berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $admin = User::findOrFail($id);

        // Prevent deleting yourself - FIXED
        if ($admin->id == Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $admin->delete();

        return redirect()->route('admin.manage')->with('success', 'Admin berhasil dihapus!');
    }


    public function verify($id)
    {
        $admin = User::findOrFail($id);

        $admin->update([
            'email_verified_at' => now()
        ]);

        return redirect()->route('admin.manage')->with('success', 'Admin berhasil diverifikasi!');
    }


    // Tambahkan method di AdminController
    public function verifyAll()
    {
        User::whereIn('role', ['admin', 'staff', 'supervisor'])
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);

        return redirect()->route('admin.manage')->with('success', 'Semua admin berhasil diverifikasi!');
    }
}
