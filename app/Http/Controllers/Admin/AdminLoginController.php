<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;

class AdminLoginController extends Controller
{
  //
  public function create(): Response
  {
    return Inertia::render('Auth/Admin/Login', [
      'canResetPassword' => Route::has('password.request'),
      'status' => session('status'),
    ]);
  }

  public function store(AdminLoginRequest $request): RedirectResponse
  {
    DB::transaction(function () use ($request) {
      $request->authenticate();

      $request->session()->regenerate();
    });
    
    return redirect()->intended(route('admin.dashboard', absolute: false));
    
  }

  public function destroy(Request $request): RedirectResponse
  {
    DB::transaction(function () use ($request) {
      Auth::guard('admin')->logout();

      $request->session()->invalidate();

      $request->session()->regenerateToken();
    });

    return redirect('/');
  }
}
