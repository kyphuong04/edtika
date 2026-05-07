<?php

namespace App\Http\Middleware;

use App\Models\AiContentTemplate;
use Closure;
use Illuminate\Support\Facades\Auth;

class PanelAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user() and !empty(apiAuth())) {
            auth()->setUser(apiAuth());
        }

        // Allow all authenticated users except Super Admins (Manager/CEO)
        // Admin role (regular admin) can access panel
        if (auth()->check() and !auth()->user()->isSuperAdmin()) {

            $referralSettings = getReferralSettings();
            view()->share('referralSettings', $referralSettings);

            $aiContentTemplates = AiContentTemplate::query()->where('enable', true)->get();
            view()->share('aiContentTemplates', $aiContentTemplates);

            view()->share('navbarPages', getNavbarLinks());

            return $next($request);
        }

        return redirect('/')->with(['auth_modal_open' => true]);
    }
}

