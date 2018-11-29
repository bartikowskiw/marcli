<?php

declare( strict_types = 1 );
namespace Umlts\Marcli\Tests;

use Umlts\Marcli\CountCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers Umlts\Marcli\CountCommand
 */

final class CountCommandTest extends TestCase {

    public $app;
    public $command;
    public $commandTester;

    public function setUp() : void {
        $this->app = new Application();
        $this->app->add( new CountCommand() );

        $this->command = $this->app->find( 'marc:count' );
        $this->commandTester = new CommandTester( $this->command ); 
    }

    public function testExecute() {
        
        $this->commandTester->execute( [
            'command'  => $this->command->getName(),
            'marc-file' => __DIR__ . '/data/random.mrc',
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertEquals( '100', trim( $output ) );

    }

}