<?php

declare( strict_types = 1 );
namespace Umlts\Marcli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Umlts\MarcToolset\MarcCount;

class CountCommand extends Command {
    
    protected function configure() {
        
        $this
            ->setName( 'marc:count' )
            ->setDescription( 'Counts the number of records in a MARC file.' )
            ->addArgument( 'marc-file', InputArgument::REQUIRED, 'Path to MARC file' );
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ) {
        $count = MarcCount::count( $input->getArgument( 'marc-file' ) );
        $output->writeln( $count );
    }
    
}
