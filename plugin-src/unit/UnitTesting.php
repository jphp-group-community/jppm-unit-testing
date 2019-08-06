<?php


namespace unit;

use packager\cli\Console;
use packager\Event;
use packager\JavaExec;
use packager\Vendor;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\lib\fs;
use php\lib\str;
use php\time\Time;

/**
 * Class UnitTesting
 *
 * @jppm-task test
 */
class UnitTesting{

    /**
     * @jppm-need-package true
     * @jppm-description Start unit testing.
     *
     * @jppm-dependency-of test
     *
     * @param Event $event
     * @throws IllegalArgumentException
     * @throws IllegalStateException
     */
    public function test(Event $event){
        $package = $event->package();

        $data = $package->getAny('test', []);
        $data['sources'] = $data['sources'] ?? ['test'];

        $vendor = new Vendor($package->getConfigVendorPath());
        $exec = new JavaExec();

        $exec->addVendorClassPath($vendor, '');
        $exec->addVendorClassPath($vendor, 'dev');
        //$exec->addVendorClassPath($vendor, 'plugin');
        $exec->addPackageClassPath($package);
        foreach($data['sources'] as $source){
            $exec->addClassPath(fs::abs("./$source"));
        }

        $sysArgs = [];
        $sysArgs['jphp.trace'] = 'true';
        $sysArgs['file.encoding'] = 'UTF-8';

        $sysArgs['unit_testing.sources'] = str::join($data['sources'], '|');
        $sysArgs['unit_testing.stacktrace'] = $event->isFlag('stacktrace');

        $sysArgs['bootstrap.file'] = 'res://unit/.unitTestingBootstrap.php';
        $sysArgs['environment'] = 'dev';

        $exec->setSystemProperties($sysArgs);
        $exec->setMainClass('php.runtime.launcher.Launcher');

        $process = $exec->run($data['args'] ?? []);
        $time = Time::millis();
        $status = $process->inheritIO()->startAndWait()->getExitValue();

        $time = Time::millis() - $time;

        Console::log("\n--> Execute time: {0} sec.", round($time / 1000, 2));

        exit($status);
    }


}