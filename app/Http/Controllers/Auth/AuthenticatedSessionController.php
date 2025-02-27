<?php

namespace App\Http\Controllers\Auth;

use App\Admin\Clients\Models\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.login');
        // return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        
        $user   = $request->user();
        $client = Client::find($user->client_id);

     
        if ($client->is_subscribed === 1) {
            return redirect()->intended(route('bsadmin.dashboard'));
        } else {
            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have access. Please subscribe to get access.',
            ]);
         

            // $request->session()->flash('error', 'You do not have access. Please subscribe to get access.');
            // return redirect()->back();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
