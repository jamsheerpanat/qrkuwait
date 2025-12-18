<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = null;
        $slug = $request->route('tenant_slug');

        if ($slug) {
            // Exclude system routes from being treated as tenant slugs
            $reserved = ['login', 'register', 'dashboard', 'admin', 'api', 'profile', 'logout'];
            if (in_array(strtolower($slug), $reserved)) {
                return $next($request);
            }

            // Validate slug: letters, numbers, dash, underscore
            if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $slug)) {
                abort(404, 'Invalid tenant slug.');
            }

            $tenant = \App\Models\Tenant::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!$tenant) {
                return response()->view('errors.tenant-not-found', ['slug' => $slug], 404);
            }
        } elseif ($request->user() && $request->user()->tenant_id) {
            $tenant = $request->user()->tenant;
        }

        if ($tenant) {
            // Set context
            $context = app(\App\Services\TenantContext::class);
            $context->setTenant($tenant);

            // Store in request
            $request->attributes->set('tenant', $tenant);

            // Share with views
            view()->share('currentTenant', $tenant);
        }

        return $next($request);
    }
}
