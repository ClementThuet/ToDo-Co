<?php

use SebastianBergmann\CodeCoverage\CodeCoverage;

$coverage = new CodeCoverage;

$coverage->filter()->addDirectoryToWhitelist('/src');

$coverage->start('ToDoCoTest');

// ...

$coverage->stop();

$writer = new \SebastianBergmann\CodeCoverage\Report\Clover;
$writer->process($coverage, '/tmp/clover.xml');

$writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
$writer->process($coverage, '/tmp/code-coverage-report');