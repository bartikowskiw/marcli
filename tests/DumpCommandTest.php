<?php

declare( strict_types = 1 );
namespace Umlts\Marcli\Tests;

use Umlts\Marcli\DumpCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers Umlts\Marcli\DumpCommand
 */

final class DumpCommandTest extends TestCase {

    public $app;
    public $command;
    public $commandTester;

    public function setUp() : void {
        $this->app = new Application();
        $this->app->add( new DumpCommand() );

        $this->command = $this->app->find( 'marc:dump' );
        $this->commandTester = new CommandTester( $this->command ); 
    }

    public function testExecute() {
        
        // Use buffer, the class dumps the contents
        // straigth to stdio
        ob_start();

        $this->commandTester->execute( [
            'command'  => $this->command->getName(),
            'marc-file' => __DIR__ . '/data/random.mrc',
        ]);
        
        // Get the output
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains( 'xtension of Bituminous Coal Act of 1937', $output );
        $this->assertContains( 'Clostridium difficile', $output );

    }

}