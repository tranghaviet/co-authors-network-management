<?php

namespace App\Http\Controllers;

use Flash;
use DB;
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

        # Check if any importing job exists
        DB::statement('SET GLOBAL max_allowed_packet=500000000');
        $importJobs = DB::select("SELECT * FROM importjobs");

        if (count($importJobs) > 0) {
            Flash::warning('Có một tiến trình đồng bộ/ nhập dữ liệu khác đang chạy, vui lòng quay lại sau ít phút.');
            return redirect()->back();
        } else {
            // Artisan::call('co-author:sync', ['--begin' => true]);
            $process = new Process('php ../artisan co-author:sync --begin');
            $process->start();
            Flash::info('Đang xử lý yêu cầu..');

            return redirect()->back();
        }
        
    }

    public function candidates() {
        # Check if any importing job exists
        DB::statement('SET GLOBAL max_allowed_packet=500000000');
        $importJobs = DB::select("SELECT * FROM importjobs");

        if (count($importJobs) > 0) {
            Flash::warning('Có một tiến trình đồng bộ/ nhập dữ liệu khác đang chạy, vui lòng quay lại sau ít phút.');
            return redirect()->back();
        } else {
            $process = new Process('php ../artisan candidate:sync');
            $process->start();
            Flash::info('Đang xử lý yêu cầu..');

            return redirect()->back();
        }
    }
}
