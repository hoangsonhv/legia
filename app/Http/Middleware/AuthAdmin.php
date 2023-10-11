<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthAdmin {

	public function handle($request, Closure $next)
	{
		
		if(!Session::has('login')) {
			Session::put('last_url', $_SERVER['REQUEST_URI']);
			return redirect()->route('admin.login.getLogin');
		}
		return $next($request);
	}
}
