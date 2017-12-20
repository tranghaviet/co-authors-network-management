<?php

namespace App\Http\Middleware;

use Closure;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;

class CheckJobEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jobs = DB::select('SELECT COUNT(pid) AS job FROM importjobs LIMIT 1;');

        if (empty($jobs[0]->job)) {
            return $next($request);
        }

        Flash::warning('System is doing some job. Come back later!');

        return redirect()->back();
    }
}
