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
            ->addArgument( 'marc-file', InputArgument::REQUIRED, 'Path to MARC file' )
            ->addArgument( 'sqlite-file', InputArgument::OPTIONAL, 'Path to SQLite file. Defaults to the MARC file name with added ".db" extension.' );

    }

    protected function initialize( InputInterface $input, OutputInterface $output ) {
        if ( empty( $input->getArgument( 'sqlite-file' ) ) ) {
            $input->setArgument( 'sqlite-file', $input->getArgument( 'marc_file'  ) . '.db' );
        }
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {

        if ( !is_readable( $input->getArgument( 'marc-file' ) ) ) {
            $output->writeln( 'Cannot read "' . $input->getArgument( 'marc-file' ) . '".' );
            exit(1);
        }

        if ( !is_writeable( $input->getArgument( 'sqlite-file' ) ) ) {
            $output->writeln( 'Cannot write to "' . $input->getArgument( 'sqlite-file' ) . '".' );
            exit(1);
        }

        $db = new \SQLite3( $input->getArgument( 'sqlite-file' ) );
        $mw = new MarcMapWriter( $input->getArgument( 'marc-file' ), $db );
        $mw->map();
    }

}
