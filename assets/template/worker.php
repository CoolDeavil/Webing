<?php


require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');

use Micro\Core\Container\MicroDI;
use Micro\Core\Utils\Logger;
use Micro\Repository\GeneralRepository;
use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$bootstrap = include(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'bootstrap.php');


$pheanstalk = Pheanstalk::create('127.0.0.1');

try {
    /**@var GeneralRepository $rep */
    $rep = MicroDI::getInstance($bootstrap)->get(GeneralRepository::class);
    Logger::log('Worker has worked....');
} catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
}


while(true){


    // we want jobs from 'testTube' only.
    $pheanstalk->watch('testTube');

// this hangs until a Job is produced.
    $job = $pheanstalk->reserve();


    try {
        $jobPayload = $job->getData();
        Logger::log( 'Incrementing hit counter on ' . (json_decode($jobPayload))->id );
        $rep->incrementViewCounter((json_decode($jobPayload))->id);
//    sleep(2);
        // If it's going to take a long time, periodically
        // tell beanstalk we're alive to stop it rescheduling the job.
//    $pheanstalk->touch($job);
        $pheanstalk->delete($job);
    }
    catch(\Exception $e) {
        // handle exception.
        // and let some other worker retry.
        $pheanstalk->release($job);
    }
}
