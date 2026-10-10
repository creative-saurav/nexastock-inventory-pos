<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend.settings.index');
    }


    /**
     * Save shop & invoice settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'invoice_note' => ['nullable', 'string', 'max:500'],
            'invoice_footer' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $values = collect($validated)
            ->except(['logo', 'remove_logo'])
            ->map(fn ($value) => $value ?? '')
            ->all();

        $currentLogo = Setting::allCached()['logo'] ?? null;

        if ($request->hasFile('logo') || $request->boolean('remove_logo')) {

            if ($currentLogo && file_exists(public_path($currentLogo))) {
                unlink(public_path($currentLogo));
            }

            $values['logo'] = '';
        }

        if ($request->hasFile('logo')) {

            $logo = $request->file('logo');

            $logoName = 'logo_' . time() . '_' . Str::random(6) . '.' . $logo->getClientOriginalExtension();

            $logo->move(public_path('uploads/settings'), $logoName);

            $values['logo'] = 'uploads/settings/' . $logoName;
        }

        Setting::saveMany($values);

        Session::flash(
            'success',
            get_phrase('Settings updated successfully!')
        );

        return redirect()->back();
    }
}
