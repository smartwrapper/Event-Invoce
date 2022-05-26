<?php 

namespace Classiebit\Eventmie\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Auth;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if(!empty(setting('multi-vendor.verify_email')) && Auth::check())
        {
            // except admin
            if(!Auth::user()->hasRole('admin'))
            {
                if (! $request->user() || ($request->user() instanceof MustVerifyEmail && ! $request->user()->hasVerifiedEmail()))
                {
                    return $request->expectsJson()
                            ? response()->json(['status' => false, 'message' => __('eventmie-pro::em.email_info')])
                            : Redirect::route($redirectToRoute ?: 'verification.notice');
                }
            }
        }

        return $next($request);
    }
}
