<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Command\RefreshDataCommand;
use Command\ClearCacheCommand;

$console = new Application('HCPSS API', '2.0.0');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

/**
 * Refresh data from remote api.
 */
$console
    ->register('data:refresh')
    ->setDefinition([])
    ->setDescription('Refresh data from the remote api.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $command = new RefreshDataCommand($app['app.root'] . 'data');
        
        $command->boeClusters();                
        $output->writeln('BOE cluster data refreshed.');
        
        $command->administrativeClusters();
        $output->writeln('Administrative cluster data refreshed.');
        
        $command->facilities();
        $output->writeln('Facility data refreshed.');
        
        $command->achievements();
        $output->writeln('Achievement data refreshed.');
    });

/**
 * Clear the cache.
 */
$console
    ->register('cache:clear')
    ->setDefinition([])
    ->setDescription('Clear cache.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $command = new ClearCacheCommand($app['app.root'] . 'var/cache');
        $command->execute();
    });

return $console;
