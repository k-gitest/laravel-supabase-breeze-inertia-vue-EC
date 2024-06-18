<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Log;

class AdminRegisterController extends Controller
{
  public function create(): Response
  {
    return Inertia::render('Auth/Admin/Register');
  }

  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|lowercase|email|max:255|unique:'.Admin::class,
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    try{
      $admin = DB::transaction(function () use ($request) {
        return $admin = Admin::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
        ]);
      });
      Log::info('Admin create succeeded');
    }
    catch (\Exception $e){
      Log::error('Failed to create admin.', ['error' => $e->getMessage()]);
      return redirect()->back()->withErrors(['error' => 'Failed to create admin. Please try again.']);
    }

    event(new Registered($admin));

    Auth::login($admin);

    return redirect(route('admin.dashboard', absolute: false));
  }
}
