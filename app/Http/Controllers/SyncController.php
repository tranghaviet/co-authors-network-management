<?php

namespace App\Http\Controllers;

use Flash;
use DB;
use Artisan;
use Symfony\Component\Process\Process as Process;

class SyncController extends AppBaseController
{
    public function index()
    {
        return view('sync.index');
    }

    public function coAuthors()
    {

        // Check if any importing job exists
        DB::statement('SET GLOBAL max_allowed_packet=500000000');
        $importJobs = DB::select('SELECT * FROM importjobs');

        if (count($importJobs) > 0) {
            Flash::warning('Có một chức năng nhập dữ liệu đang được thực hiện, bạn vui lòng quay lại sau ít phút');

            return redirect()->back();
        } else {
            // Artisan::call('co-author:sync', ['--begin' => true]);
            $process = new Process('php ../artisan co-author:sync --begin');
            
            \Log::info('Sync coauthor controller: Adding job to database: sync_coauthor');
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'sync_coauthor')");
            \Log::info('Sync coauthor controller: Job added todatabase, now start process');
            \Log::info(microtime(true));
            $process->start();
            Flash::info('In progress..');

            return redirect()->back();
        }
    }

    public function candidates()
    {
        // Check if any importing job exists
        DB::statement('SET GLOBAL max_allowed_packet=500000000');
        $importJobs = DB::select('SELECT * FROM importjobs');
        if (count($importJobs) > 0) {
            Flash::warning('Có một chức năng nhập dữ liệu đang được thực hiện, bạn vui lòng quay lại sau ít phút');

            return redirect()->back();
        } else {
            $process = new Process('php ../artisan candidate:sync');
            \Log::info('Sync candidate controller: Adding job to database: sync_candidate');
            \DB::statement('INSERT INTO importjobs VALUES ('.getmypid().", 'sync_candidate')");
            \Log::info('Sync candidate controller: Job added todatabase, now start process');
            \Log::info(microtime(true));
            $process->start();
            Flash::info('In progress..');

            return redirect()->back();
        }
    }
}
