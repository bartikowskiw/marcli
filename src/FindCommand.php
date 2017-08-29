<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcFind;
use Umlts\MarcToolset\MarcMask;

class FindCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'marc:find' )
            ->setDescription( 'Finds records.' )
            ->addArgument( 'tag', InputArgument::REQUIRED )
            ->addArgument( 'needle', InputArgument::REQUIRED )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' )
            ->addOption( 'raw', 'r', InputOption::VALUE_NONE, 'Raw MARC output?' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $mask = new MarcMask(
                    $input->getArgument( 'tag' ),
                    '.', '.', '.*',
                    $input->getArgument( 'needle' )
                );

        $mf = new MarcFind( $input->getArgument( 'marc-file' ), $mask );

        if ( $input->getOption( 'raw' ) ) {
            $mf->echoRaw();
        } else {
            $mf->echoDump( !$input->getOption( 'no-ansi' ) );
        }
    }

}
