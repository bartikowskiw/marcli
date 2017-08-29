<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Umlts\MarcToolset\MarcFind;

class FindCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'marc:find' )
            ->setDescription( 'Finds records.' )
            ->addArgument( 'marc-file', InputArgument::REQUIRED, 'Path to MARC file' )
            ->addArgument( 'tag', InputArgument::REQUIRED, 'MARC Tag (regular expression)' )
            ->addArgument( 'needle', InputArgument::REQUIRED, 'Search term (regular expression)' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $mf = new MarcFind( $input->getArgument( 'marc-file' ) );
        $mf->find(
            $input->getArgument( 'tag' ),
            $input->getArgument( 'needle' ),
            !$input->getOption( 'no-ansi' )
        );
    }

}
