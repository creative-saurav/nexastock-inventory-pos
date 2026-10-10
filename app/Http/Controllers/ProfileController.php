<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->only(['name', 'email', 'phone']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Replace or remove the profile photo
        if ($request->hasFile('image') || $request->boolean('remove_image')) {

            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $user->image = null;
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . Str::random(6) . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/users'), $imageName);

            $user->image = 'uploads/users/' . $imageName;
        }

        $user->save();

        Session::flash('success', get_phrase('Profile updated successfully!'));

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
