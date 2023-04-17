<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected const HEADER_FORMAT = 'frame-ancestors %s %s';
    protected const ADMIN_SHOPIFY_URL = 'https://admin.shopify.com';
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof HttpResponse && !$request->ajax()) {

            if ($request->has('shop')) {
                $shopDomain = ShopDomain::fromNative($request->get('shop'));
            }
            elseif ($request->user() instanceof User) {
                $shopDomain = $request->user()->getDomain();
            }
            else {
                $shopDomain = ShopDomain::fromRequest($request);
            }
            if ($shopDomain instanceof ShopDomain) {
                $response->header('Content-Security-Policy', sprintf(self::HEADER_FORMAT, 'https://' . $shopDomain->toNative(), self::ADMIN_SHOPIFY_URL));
            }
        }
        return $response;
    }
}

