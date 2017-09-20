<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Umlts\MarcToolset\MarcMapWriter;

class MapWriteCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'map:write' )
            ->setDescription( 'Creates a SQLite DB with the ids and positions of the records.' )
            ->addArgument( 'marc_file', InputArgument::REQUIRED, 'Path to MARC file' )
            ->addArgument( 'sqlite_file', InputArgument::OPTIONAL, 'Path to SQLite file. Defaults to the MARC file name with added ".db" extension.' );

    }

    protected function initialize( InputInterface $input, OutputInterface $output ) {
        if ( empty( $input->getArgument( 'sqlite_file' ) ) ) {
            $input->setArgument( 'sqlite_file', $input->getArgument( 'marc_file'  ) . '.db' );
        }
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $db = new \SQLite3( $input->getArgument( 'sqlite_file' ) );
        $mw = new MarcMapWriter( $input->getArgument( 'marc_file' ), $db );
        $mw->map();
    }

}
