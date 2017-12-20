<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Excel;
use Cache;
use DB;
use Flash;
use Symfony\Component\Process\Process as Process;

class ImportAuthor_PaperController extends Controller
{
    public function view_upload_authors_papers()
    {
        return view('upload.upload_authors_papers');
    }

    public function upload_authors_papers(Request $request)
    {
        if (Input::hasFile('file')) {
            $path = Input::file('file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get()->toArray();
            $n = count($data);

            // Check if any importing job exists
            DB::statement('SET GLOBAL max_allowed_packet=500000000');
            $importJobs = DB::select('SELECT * FROM importjobs');

            if (count($importJobs) > 0) {
                Flash::warning('Có một chức năng nhập dữ liệu đang được thực hiện, bạn vui lòng quay lại sau ít phút');

                return redirect()->back();
            } else {
                if (! empty($data) && $n) {
                    // dump('Put author paper data to cache');
                    Cache::put('author_paper_lines', $data, 200);

                    $numProcesses = 15.0;
                    $limit = intval(ceil($n / $numProcesses));
                    $i = 0;
                    while ($i < $numProcesses) {
                        $offset = $i * $limit;
                        $l = $n - $offset < $limit ? $n - $offset : $limit;
                        if ($offset >= $n) {
                            break;
                        }
                        // dump('start import author paper with limit '.strval($l).' and offset '. strval($offset) .'.');
                        $process = new Process('php ../artisan import:author_paper --offset='. strval($offset) .' '. '--limit='. strval($l) .'');
                        $process->start();

                        $i++;
                    }
                    sleep(1);
                    Flash::info('In processing. Please wait');

                    return redirect()->back();
                } else {
                    Flash::info('Done');

                    return redirect()->back();
                }
            }
        } else {
            Flash::error('Yêu cầu nhập file dữ liệu');

            return redirect()->back();
        }
    }
}
