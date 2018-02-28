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

        if ( !is_readable( $input->getArgument( 'marc-file1' ) ) ) {
            echo 'Cannot read "' . $input->getArgument( 'marc-file1' ) . '"', PHP_EOL;
            exit(1);
        }

        if ( $input->getArgument( 'marc-file2' ) !== 'php://stdin'
            && !is_readable( $input->getArgument( 'marc-file2' ) ) ) {
              echo 'Cannot read "' . $input->getArgument( 'marc-file2' ) . '"', PHP_EOL;
              exit(1);
        }

        $bool = new MarcBool( $input->getArgument( 'marc-file1' ), $input->getArgument( 'marc-file2' ) );
        $bool->boolAnd();
        if ( $input->getOption( 'raw' ) ) {
            $bool->echoRaw();
        } else {
            $bool->echoDump( !$input->getOption( 'no-ansi' ) );
        }
    }

}
