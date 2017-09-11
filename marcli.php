<?php

declare( strict_types = 1 );

if ( version_compare( phpversion(), '7.0', '<' ) ) {
    die( 'This script needs at least PHP version 7.0! (Running on PHP ' . phpversion() .' now.)' . "\n" );
}


// http://www.patorjk.com/software/taag/#f=Elite&t=MarCLI
define( 'MARCLI_BANNER', "\e[33m
 • ▌ ▄ ·.  ▄▄▄· ▄▄▄   ▄▄· ▄▄▌  ▪
 ·██ ▐███▪▐█ ▀█ ▀▄ █·▐█ ▌▪██•  ██
 ▐█ ▌▐▌▐█·▄█▀▀█ ▐▀▀▄ ██ ▄▄██▪  ▐█·
 ██ ██▌▐█▌▐█ ▪▐▌▐█•█▌▐███▌▐█▌▐▌▐█▌
 ▀▀  █▪▀▀▀ ▀  ▀ .▀  ▀·▀▀▀ .▀▀▀ ▀▀▀\e[0m\e[32m

 \e[32m▪▪▪▪ \e[37mMARC Command Line Tools\e[32m ▪▪▪▪\e[0m

" );
require_once __DIR__ . '/vendor/autoload.php';


use Symfony\Component\Console\Application;
use Umlts\Marcli\CountCommand;
use Umlts\Marcli\DumpCommand;
use Umlts\Marcli\LintCommand;
use Umlts\Marcli\FindCommand;
use Umlts\Marcli\ReplaceCommand;
use Umlts\Marcli\MapWriteCommand;
use Umlts\Marcli\MapReadCommand;

$app = new Application( MARCLI_BANNER, 'dev' );

$app->add( new DumpCommand() );
$app->add( new CountCommand() );
$app->add( new LintCommand() );
$app->add( new FindCommand() );
$app->add( new ReplaceCommand() );
$app->add( new MapWriteCommand() );
$app->add( new MapReadCommand() );

$app->run();
