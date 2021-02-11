<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;

class LoginConfigurationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $config = Configuration::first();

        return view('tenant.login_page.index', compact('user', 'config'));
    }

    public function uploadBgImage()
    {
        request()->validate([
            'image' => 'required|mimes:webp,jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $config = Configuration::first();
        if (request()->hasFile('image') && request()->file('image')->isValid()) {
            $file = request()->file('image');
            $ext = $file->getClientOriginalExtension();
            $name = time() . '.' . $ext;
            $path = 'public/uploads/login';
            $file->storeAs($path, $name);

            $loginConfig = $config->login;
            $loginConfig->type = 'image';
            $loginConfig->image = asset('storage/uploads/login/' . $name);
            $config->login = $loginConfig;
            $config->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Información actualizada.',
        ], 200);
    }
}
