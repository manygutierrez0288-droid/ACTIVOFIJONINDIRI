<?php

namespace App\Http\Controllers;


use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return Inertia::render('Settings/Index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                if ($request->hasFile($key)) {
                    // Delete old file if exists
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('settings', 'public');
                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            }
        }

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
