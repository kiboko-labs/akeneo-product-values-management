<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Kiboko\Component\AkeneoProductValues\Command;
use League\Flysystem\Adapter;
use League\Flysystem\Filesystem;

$application = new Application();

$commands = [
    new Command\InitCommand(),
//    new Command\ReferenceData\AddCommand(),
    new Command\ReferenceData\BuildCommand(),
//    new Command\ReferenceData\RemoveCommand(),
];

$filesystem = new Filesystem(
    new Adapter\Local(__DIR__ . '/../testing/')
);

array_walk($commands, function($current, $index, Filesystem $filesystem) {
    if (!$current instanceof Command\FilesystemAwareInterface) {
        return;
    }
    $current->setFilesystem($filesystem);
}, $filesystem);

$application->addCommands(
    $commands
);

$application->run();


//$nodeTraverser->addVisitor(new BundleBuildMethodVisitor(
//    [
//        new CompilerPassRegistrationCodeGenerator(
//            'Kiboko\\Bundle\\MagentoReferenceDataBundle\\DependencyInjection\\FooCompilerPass'
//        ),
//        new CompilerPassRegistrationCodeGenerator(
//            'Kiboko\\Bundle\\MagentoReferenceDataBundle\\DependencyInjection\\BarCompilerPass'
//        ),
//        new CompilerPassRegistrationCodeGenerator(
//            'Kiboko\\Bundle\\MagentoReferenceDataBundle\\DependencyInjection\\BazCompilerPass'
//        ),
//    ]
//));
