<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;
use Artisan;
use Symfony\Component\Process\Process as Process;

class TestProcessController extends Controller
{
	public function testprocess(){

		// $process1=new Process(print('adasdd'));
		// $process2=new Process(dd(''));
		// $p3 = new Process('php artisan co-author:sync');
		// $process1->start();
		// var_dump("expression");
		// $directory='C:\xampp7\htdocs\co-authors-network-management';
		// $process = new Process('cd '.$directory.' && php artisan foo:name');
        // $process->setTimeout(3600);
        // $process->setPty(true);
        // dd('dadada');
        Artisan::call('co-author:sync', ['--begin' => true]);
        dump('Sync coauthor OK');
		Artisan::call('candidate:sync');
		dump('OK');

        // $process->run(function ($type, $buffer) {
        //     echo $buffer;
        // });
	}
}