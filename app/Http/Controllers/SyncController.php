<?php

namespace App\Http\Controllers;

use Flash;
use Artisan;

class SyncController extends AppBaseController
{
    public function index()
    {
        return view('sync.index');
    }

    public function coAuthors()
    {
        Artisan::call('co-author:sync', ['--begin' => true]);
        Flash::success('Done Co-authors sync.');

        return redirect()->back();
    }

    public function candidates() {
        try {
            Artisan::call('candidate:sync');
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return  redirect('/');
        }
   
        Flash::success('Done Candidates sync.');

        return redirect()->back();
    }
}
