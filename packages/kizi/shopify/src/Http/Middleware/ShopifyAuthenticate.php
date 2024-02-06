<?php
namespace Kizi\Shopify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ShopifyAuthenticate 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hmac = $request->input('hmac');
        $host = $request->input('host');
        $code = $request->input('code');
        $shop = $request->input('shop');
        $session = $request->input('session');
        $timestamp = $request->input('timestamp');
        if(!isset($session) && Route::currentRouteName() == 'shopify.redirect'){
            if(isset($hmac) && isset($host) && isset($shop) && isset($timestamp)){
                if(isset($code)){
                    // $auth = Shopify::auth($request->all());
                    $appUrl = Shopify::setDomain($request->get('shop'))->appUrl();
                    return redirect($appUrl);
                }else{
                    $installUrl = Shopify::setDomain($request->get('shop'))->installUrl();
                    return redirect($installUrl);
                }
            }
        }

        return $next($request);
    }
}
