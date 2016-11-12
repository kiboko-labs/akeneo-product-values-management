<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommands(
    [
        new Kiboko\Component\AkeneoProductValues\Command\InitCommand(),
    ]
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
