<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Umlts\MarcToolset\MarcLint;

class LintCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'marc:lint' )
            ->setDescription( 'Checks records in a MARC file for errors.' )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        MarcLint::check( $input->getArgument( 'marc-file' ),!$input->getOption( 'no-ansi' ) );
    }

}
