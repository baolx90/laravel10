<?php
namespace Kizi\Shopify\Http\Controllers;

use Illuminate\Http\Request;
use Kizi\Shopify\Facades\Shopify;

class ShopifyController extends Controller
{
    public function redirect(Request $request)
    {
        return redirect(config('shopify.redirect_home'));
    }
}