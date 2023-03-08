<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Lp;
use App\Models\Retailer;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        if (Auth::user()->hasRole('Retailer') || Auth::user()->hasRole('Lp')) {
            $id = Auth::user()->userable_id;
            $check = '';
            if (Auth::user()->userable_type == 'App\Models\Retailer') {
                $retailer = Retailer::where('id', $id)->where('status', '!=', 'Approved')->first();
                if ($retailer) {
                    Auth::guard('web')->logout();

                    $messages['danger'] = "Unauthorized";

                    return redirect()
                        ->back()
                        ->with('messages', $messages);
                } else {
                    $request->session()->regenerate();
                    $messages['success'] = "Welcome to IRCC Data Portal";
                    return redirect()->route('reports.monthly.status')
                        ->with('messages', $messages);
                }
            } elseif (Auth::user()->userable_type == 'App\Models\Lp') {
                $lp = Lp::where('id', $id)->where('status', '!=', 'Approved')->first();
                if ($lp) {
                    Auth::guard('web')->logout();

                    $messages['danger'] = "Unauthorized";
                    return redirect()
                        ->back()
                        ->with('messages', $messages);
                }
            }
        }
        $request->session()->regenerate();
        $messages['success'] = "Welcome to IRCC Data Portal";

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('messages', $messages);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
