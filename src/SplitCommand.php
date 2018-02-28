<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcSplit;

class SplitCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'marc:split' )
            ->setDescription( 'Split MARC files.' )
            ->addArgument( 'size', InputArgument::REQUIRED, 'Size of the chunks' )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' )
            ->addOption( 'output-dir', NULL, InputOption::VALUE_REQUIRED, 'Output directory', './' )
            ->addOption( 'enum-length', NULL, InputOption::VALUE_REQUIRED, 'Length of the enumeration string.', 3 )
            ->addOption( 'enum-type-chars', NULL, InputOption::VALUE_NONE, 'Alphabetic enumeration' )
        ;
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {

        if ( $input->getArgument( 'marc-file' ) !== 'php://stdin'
          && !is_readable( $input->getArgument( 'marc-file' ) ) ) {
            echo 'Cannot read "' . $input->getArgument( 'marc-file' ) . '"', PHP_EOL;
            exit(1);
        }

        $ms = new MarcSplit( $input->getArgument( 'marc-file' ) );

        $ms
            ->setOutputDir( $input->getOption( 'output-dir' ) )
            ->setEnumLength( (int) $input->getOption( 'enum-length' ) )
        ;

        if ( $input->getOption( 'enum-type-chars' ) ) {
            $ms
                ->setEnumChars( implode( range( 'a', 'z' ) )  );
        }

        $ms->split( (int) $input->getArgument( 'size' ) );
    }

}
