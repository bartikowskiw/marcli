<?php

declare( strict_types = 1 );
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Umlts\Marcli\CountCommand;
use Umlts\Marcli\DumpCommand;
use Umlts\Marcli\LintCommand;
use Umlts\Marcli\FindCommand;
use Umlts\Marcli\MapWriteCommand;
use Umlts\Marcli\MapReadCommand;

$app = new Application( 'Marcli. MARC CLI Tools.', 'dev' );

$app->add( new DumpCommand() );
$app->add( new CountCommand() );
$app->add( new LintCommand() );
$app->add( new FindCommand() );
$app->add( new MapWriteCommand() );
$app->add( new MapReadCommand() );

$app->run();
