<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        // VALIDASI SEDERHANA DULU
        $rules = [
            'name' => 'required|string|max:255',
            'email_valid' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // UPDATE DATA DASAR
            $user->name = $request->name;
            $user->email_valid = $request->email_valid;
            $user->phone = $request->phone;
            $user->position = $request->position;
            
            // EMAIL LOGIN TIDAK DIUPDATE - Hanya superadmin yang bisa edit

            // HANDLE FOTO
            if ($request->hasFile('profile_photo')) {
                // Hapus foto lama
                if ($user->profile_photo) {
                    $oldPath = 'profile-photos/' . $user->profile_photo;
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                // Simpan foto baru
                $file = $request->file('profile_photo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('profile-photos', $filename, 'public');
                $user->profile_photo = $filename;
            }

            // HANDLE PASSWORD
            if ($request->filled('current_password')) {
                if (Hash::check($request->current_password, $user->password)) {
                    $user->password = Hash::make($request->new_password);
                } else {
                    return redirect()->back()
                        ->withErrors(['current_password' => 'Current password is incorrect'])
                        ->withInput();
                }
            }

            // SIMPAN SEMUA PERUBAHAN
            $user->save();

            return redirect()->route('profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }
}