<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Setting;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {

        $data = Operator::first();
        return view('tu.settings', compact('data'));
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\Operator $operator */
        $operator = Operator::where('operator_id', $id)->firstOrFail();

        $request->validate([
            'name'             => 'required|string|max:100', 
            'username'         => ['required', Rule::unique('operators', 'username')->ignore($operator->operator_id, 'operator_id')],
            'photo_url'        => 'nullable|image|max:2048',
            'current_password' => 'required_with:new_password,username',
            'new_password'     => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $operator->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
        }

        $operator->name = $request->name;
        $operator->username = $request->username;

        if ($request->filled('new_password')) {
            $operator->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('photo_url')) {
            if ($operator->photo_url && file_exists(public_path($operator->photo_url))) {
                unlink(public_path($operator->photo_url));
            }

            $file = $request->file('photo_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/operator'), $filename);
            $operator->photo_url = 'img/operator/' . $filename;
        }

        $operator->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function index_landing()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('tu.landing.settings', compact('settings'));
    }

    public function update_landing(Request $request)
    {
        $data = $request->except(['_token', '_method', 'hero_bg', 'school_logo', 'history_image']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('hero_bg')) {
            $request->validate(['hero_bg' => 'image|mimes:jpeg,png,jpg|max:2048']);

            $oldImage = Setting::where('key', 'hero_bg')->value('value');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('hero_bg')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'hero_bg'], ['value' => $path]);
        }

        if ($request->hasFile('struktur_img')) {
            $request->validate(['struktur_img' => 'image|mimes:jpeg,png,jpg|max:2048']);

            $oldStruktur = Setting::where('key', 'struktur_img')->value('value');
            if ($oldStruktur) {
                Storage::disk('public')->delete($oldStruktur);
            }

            $path = $request->file('struktur_img')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'struktur_img'], ['value' => $path]);
        }

        if ($request->hasFile('school_logo')) {
            $request->validate(['school_logo' => 'image|mimes:png,jpg,jpeg|max:2048']);
            $oldLogo = Setting::where('key', 'school_logo')->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('school_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'school_logo'], ['value' => $path]);
        }

        if ($request->hasFile('history_image')) {
            $request->validate(['history_image' => 'image|mimes:png,jpg,jpeg|max:2048']);
            $oldHistory = Setting::where('key', 'history_image')->value('value');
            if ($oldHistory) {
                Storage::disk('public')->delete($oldHistory);
            }

            $path = $request->file('history_image')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'history_image'], ['value' => $path]);
        }

        return back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
