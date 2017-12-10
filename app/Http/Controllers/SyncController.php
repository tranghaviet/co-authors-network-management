<?php

namespace App\Http\Controllers;

use Flash;
use Artisan;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SyncController extends AppBaseController
{
    public function index()
    {
        return view('sync.index');
    }

    public function coAuthors()
    {
        // Artisan::call('co-author:sync', ['--begin' => true]);
        $process = new Process('php ../artisan co-author:sync --begin');
        $process->start();
        Flash::info('In progress..');

        return redirect()->back();
    }

    public function candidates() {
        $process = new Process('php ../artisan candidate:sync');
        $process->start();
        Flash::info('In progress..');

        return redirect()->back();
    }
}
