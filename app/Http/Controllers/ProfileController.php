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
                // Hapus foto lama dari Cloudinary
                if ($user->profile_photo && str_starts_with($user->profile_photo, 'http')) {
                    // Extract public_id from Cloudinary URL and delete
                    try {
                        $publicId = pathinfo(parse_url($user->profile_photo, PHP_URL_PATH), PATHINFO_FILENAME);
                        \Cloudinary\Cloudinary::uploadApi()->destroy('profile-photos/' . $publicId);
                    } catch (\Exception $e) {
                        // Ignore delete errors
                    }
                } elseif ($user->profile_photo) {
                    // Legacy local storage cleanup
                    if (Storage::disk('public')->exists('profile-photos/' . $user->profile_photo)) {
                        Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
                    }
                }
                
                // Upload ke Cloudinary
                $uploadedFile = cloudinary()->upload($request->file('profile_photo')->getRealPath(), [
                    'folder' => 'profile-photos',
                    'transformation' => ['width' => 400, 'height' => 400, 'crop' => 'fill']
                ]);
                $user->profile_photo = $uploadedFile->getSecurePath();
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