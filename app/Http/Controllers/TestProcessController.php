<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;
use Symfony\Component\Process\Process as Process;

class TestProcessController extends Controller
{
	public function testprocess(){

		$process1=new Process(print('adasdd'));
		$process2=new Process(dd(''));
		$p3 = new Process('php artisan co-author:sync');
		$process1->start();
		var_dump("expression");
	}
}