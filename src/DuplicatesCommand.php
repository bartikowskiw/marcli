<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcDuplicates;

class DuplicatesCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'search:duplicates' )
            ->setDescription( 'Searches for duplicates.' )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' )
            ->addOption( 'raw', 'r', InputOption::VALUE_NONE, 'Raw MARC output?' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $dup = new MarcDuplicates( $input->getArgument( 'marc-file' ) );
        $dup->findDuplicates();
        if ( $input->getOption( 'raw' ) ) {
            $dup->echoRaw();
        } else {
            $dup->echoDump( !$input->getOption( 'no-ansi' ) );
        }
    }

}
