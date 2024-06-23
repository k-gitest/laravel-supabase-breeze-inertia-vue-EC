<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProfileUpdateRequest;

class AdminProfileController extends Controller
{
  /**
   * Display the user's profile form.
   */
  public function edit(Request $request): Response
  {
    return Inertia::render('Profile/Admin/Edit', [
      'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
      'status' => session('status'),
    ]);
  }

  /**
   * Update the user's profile information.
   */
  public function update(AdminProfileUpdateRequest $request): RedirectResponse
  {
    $request->user()->fill($request->validated());

    if ($request->user()->isDirty('email')) {
      $request->user()->email_verified_at = null;
    }

    DB::transaction(function () use ($request) {
      $request->user()->save();
    });

    return Redirect::route('admin.profile.edit');
  }

  /**
   * Delete the user's account.
   */
  public function destroy(Request $request): RedirectResponse
  {
    $request->validate([
      'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();
    
    DB::transaction(function () use ($request) {
      $user->delete();
    });

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
  }
}
