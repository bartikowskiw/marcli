<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Umlts\MarcToolset\MarcCount;

class CountCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'marc:count' )
            ->setDescription( 'Counts the number of records in a MARC file.' )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        if ( $input->getArgument( 'marc-file' ) !== 'php://stdin'
          && !is_readable( $input->getArgument( 'marc-file' ) ) ) {
            $output->writeln( 'Cannot read "' . $input->getArgument( 'marc-file' ) . '"' );
            exit(1);
        }

        $count = MarcCount::count( $input->getArgument( 'marc-file' ) );
        $output->writeln( $count );
    }

}
