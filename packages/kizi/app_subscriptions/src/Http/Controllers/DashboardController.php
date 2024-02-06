<?php
namespace Kizi\AppSubscriptions\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('app_subscriptions::dashboard');
    }
}