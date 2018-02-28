<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcDump;

class DumpCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'marc:dump' )
            ->setDescription( 'Dumps the content of a MARC file in human-readable form.' )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        
        if ( $input->getArgument( 'marc-file' ) !== 'php://stdin'
          && !is_readable( $input->getArgument( 'marc-file' ) ) ) {
            echo 'Cannot read "' . $input->getArgument( 'marc-file' ) . '"', PHP_EOL;
            exit(1);
        }

        echo MarcDump::dump( $input->getArgument( 'marc-file' ), !$input->getOption( 'no-ansi' ) );
    }

}
