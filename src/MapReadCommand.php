<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Umlts\MarcToolset\MarcMapReader;
use Umlts\MarcToolset\MarcDump;

class MapReadCommand extends Command {
    
    protected function configure() {
        
        $this
            ->setName( 'map:read' )
            ->setDescription( 'Reads a record from a lookup table created by map:write.' )
            ->addArgument( 'record-id', InputArgument::REQUIRED, 'The record id. Probably the OCLC number (without prefix)' )
            ->addArgument( 'marc-file', InputArgument::REQUIRED, 'Path to MARC file' )
            ->addArgument( 'sqlite-file', InputArgument::REQUIRED, 'Path to SQLite file' );
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ) {
        $db = new \SQLite3( $input->getArgument( 'sqlite-file' ) );
        $mr = new MarcMapReader( $input->getArgument( 'marc-file' ), $db );
        try {
            $output->writeln(
                MarcDump::formatDump( (string) $mr->get( (int) $input->getArgument( 'record-id' ) ) )
            );
        } catch ( MarcRecordNotFoundException $e ) {
            $output->writeln( 'Record not found.' );
        }

    }
    
}
