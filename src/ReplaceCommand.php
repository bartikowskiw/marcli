<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Umlts\MarcToolset\MarcReplace;
use Umlts\MarcToolset\MarcMask;

class ReplaceCommand extends Command {

    protected function configure() {

        $this
            ->setName( 'search:replace' )
            ->setDescription( 'Finds and replaces fields in records.' )
            ->addArgument( 'needle', InputArgument::REQUIRED, 'Needle (PCRE expression)' )
            ->addArgument( 'replace', InputArgument::REQUIRED, 'Replacement. See <https://secure.php.net/manual/en/function.preg-replace.php>.' )
            ->addArgument( 'marc-file', InputArgument::OPTIONAL, 'Path to MARC file', 'php://stdin' )
            ->addOption( 'tag', 't', InputOption::VALUE_REQUIRED, 'Marc Tag', '...' )
            ->addOption( 'ind1', NULL, InputOption::VALUE_REQUIRED, 'Indicator 1', '.' )
            ->addOption( 'ind2', NULL, InputOption::VALUE_REQUIRED, 'Indicator 2', '.' )
            ->addOption( 'sub', 's', InputOption::VALUE_REQUIRED, 'Subfield', '.' )
            ->addOption( 'raw', 'r', InputOption::VALUE_NONE, 'Raw MARC output?' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {

        if ( $input->getArgument( 'marc-file' ) !== 'php://stdin'
          && !is_readable( $input->getArgument( 'marc-file' ) ) ) {
            echo 'Cannot read "' . $input->getArgument( 'marc-file' ) . '"', PHP_EOL;
            exit(1);
        }

        $mask = new MarcMask(
                    $input->getOption( 'tag' ),
                    $input->getOption( 'ind1' ),
                    $input->getOption( 'ind2' ),
                    $input->getOption( 'sub' ),
                    $input->getArgument( 'needle' )
                );

        $mr = new MarcReplace( $input->getArgument( 'marc-file' ), $mask, $input->getArgument( 'replace' ) );

        if ( $input->getOption( 'raw' ) ) {
            $mr->echoRaw();
        } else {
            $mr->echoDump( !$input->getOption( 'no-ansi' ) );
        }
    }

}
