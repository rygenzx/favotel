<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::all();

        if (count($setting)) {
            $setting = $setting[0];
        } else {
            $setting = [];
        }

        return view('/pages/admin/setting/index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data Pengaturan gagal diperbarui');
        }

        $settings = Setting::all();

        if (count($settings)) {
            $setting = $settings[0];

            $setting->update([
                'name' => $request->name,
                'description' => $request->description,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            if ($request->hasFile('short_logo')) {
                $rand = Str::random(20);
                $name_image = $rand . "." . $request->short_logo->getClientOriginalExtension();
                $request->file('short_logo')->move('images/uploads/setting', $name_image);
                $setting->short_logo = $name_image;
                $setting->save();
            }

            if ($request->hasFile('long_logo')) {
                $rand = Str::random(20);
                $name_image = $rand . "." . $request->long_logo->getClientOriginalExtension();
                $request->file('long_logo')->move('images/uploads/setting', $name_image);
                $setting->long_logo = $name_image;
                $setting->save();
            }
        } else {
            $setting = Setting::create([
                'name' => $request->name,
                'description' => $request->description,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            if ($request->hasFile('short_logo')) {
                $rand = Str::random(20);
                $name_image = $rand . "." . $request->short_logo->getClientOriginalExtension();
                $request->file('short_logo')->move('images/uploads/setting', $name_image);
                $setting->short_logo = $name_image;
                $setting->save();
            }

            if ($request->hasFile('long_logo')) {
                $rand = Str::random(20);
                $name_image = $rand . "." . $request->long_logo->getClientOriginalExtension();
                $request->file('long_logo')->move('images/uploads/setting', $name_image);
                $setting->long_logo = $name_image;
                $setting->save();
            }
        }

        return redirect()->back()->with('success', 'Data Pengaturan berhasil diperbarui');
    }
}
