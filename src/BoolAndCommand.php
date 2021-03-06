<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcBool;

class BoolAndCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'bool:and' )
            ->setDescription( 'Records that are in both files. Returns records from the first file.' )
            ->addArgument( 'marc-file1', InputArgument::REQUIRED, 'Path to first MARC file' )
            ->addArgument( 'marc-file2', InputArgument::OPTIONAL, 'Path to second MARC file', 'php://stdin' )
            ->addOption( 'raw', 'r', InputOption::VALUE_NONE, 'Raw MARC output?' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $bool = new MarcBool( $input->getArgument( 'marc-file1' ), $input->getArgument( 'marc-file2' ) );
        $bool->boolAnd();
        if ( $input->getOption( 'raw' ) ) {
            $bool->echoRaw();
        } else {
            $bool->echoDump( !$input->getOption( 'no-ansi' ) );
        }
    }

}
