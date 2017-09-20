<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcMapReader;
use Umlts\MarcToolset\MarcDump;

class MapReadCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'map:read' )
            ->setDescription( 'Reads a record from a lookup table created by map:write.' )
            ->addArgument( 'record_id', InputArgument::REQUIRED, 'The record id. Probably the OCLC number.' )
            ->addArgument( 'marc_file', InputArgument::REQUIRED, 'Path to MARC file' )
            ->addArgument( 'sqlite_file', InputArgument::OPTIONAL, 'Path to SQLite file. Defaults to the MARC file name with added ".db" extension.' )
            ->addOption( 'raw', 'r', InputOption::VALUE_NONE, 'Raw MARC output?' );
    }

    protected function initialize( InputInterface $input, OutputInterface $output ) {
        if ( empty( $input->getArgument( 'sqlite_file' ) ) ) {
            $input->setArgument( 'sqlite_file', $input->getArgument( 'marc_file'  ) . '.db' );
        }
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $db = new \SQLite3( $input->getArgument( 'sqlite_file' ) );
        $mr = new MarcMapReader( $input->getArgument( 'marc_file' ), $db );
        try {
            $records = $mr->get( $input->getArgument( 'record_id' ) );
            foreach ( $records as $record ) {
                if ( $input->getOption( 'raw' ) ) {
                        $output->writeln( $record->toRaw() );
                } else {
                    $output->writeln(
                        MarcDump::formatDump( (string) $record )
                    );
                }
            }
        } catch ( MarcRecordNotFoundException $e ) {
            $output->writeln( 'Record not found.' );
        }

    }

}
