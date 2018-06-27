<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Umlts\MarcToolset\MarcDump;
use Umlts\MarcToolset\MarcMapReader;
use Umlts\MarcToolset\MarcRecordNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MapReadCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'map:read' )
            ->setDescription( 'Reads a record from a lookup table created by map:write.' )
            ->addArgument( 'record-id', InputArgument::REQUIRED, 'The record id. Probably the OCLC number.' )
            ->addArgument( 'marc-file', InputArgument::REQUIRED, 'Path to MARC file' )
            ->addArgument( 'sqlite-file', InputArgument::OPTIONAL, 'Path to SQLite file. Defaults to the MARC file name with added ".db" extension.' )
            ->addOption( 'raw', 'r', InputOption::VALUE_NONE, 'Raw MARC output?' )
            ->addOption( 'regexp', 'e', InputOption::VALUE_NONE, 'record_id is a regular expression' );
    }

    protected function initialize( InputInterface $input, OutputInterface $output ) {
        if ( empty( $input->getArgument( 'sqlite-file' ) ) ) {
            $input->setArgument( 'sqlite-file', $input->getArgument( 'marc-file'  ) . '.db' );
        }
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {

        if ( !is_readable( $input->getArgument( 'marc-file' ) ) ) {
            echo 'Cannot read "' . $input->getArgument( 'marc-file' ) . '"', PHP_EOL;
            exit(1);
        }

        $db = new \SQLite3( $input->getArgument( 'sqlite-file' ) );
        $mr = new MarcMapReader( $input->getArgument( 'marc-file' ), $db );
        try {
            $records = $mr->get(
                $input->getArgument( 'record-id'),
                $input->getOption( 'regexp' )
            );

            foreach ( $records as $record ) {

                if ( $input->getOption( 'raw' ) ) {
                    $output->writeln( $record->toRaw() );
                    continue;
                }

                if ( $input->getOption( 'no-ansi' ) ) {
                    $output->writeln( (string) $record );
                    continue;
                }

                $output->writeln( MarcDump::formatDump( (string) $record ) );
            }
        } catch ( MarcRecordNotFoundException $e ) {
            $output->writeln( 'Record not found.' );
        }

    }

}
