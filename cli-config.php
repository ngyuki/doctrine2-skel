<?php
$isDevMode = true;

$params = require __DIR__ . '/db-params.php';

$config = \Doctrine\ORM\Tools\Setup::createXMLMetadataConfiguration(array(__DIR__ . '/xml'), $isDevMode);
$entityManager = \Doctrine\ORM\EntityManager::create($params, $config);
$helperSet = \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
$helperSet->set(new \Symfony\Component\Console\Helper\QuestionHelper(), 'dialog');

$commands[] = new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand();
$commands[] = new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand();
$commands[] = new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand();
$commands[] = new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand();
$commands[] = new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand();
$commands[] = new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand() ;

spl_autoload_register(function ($name) {
    $fn = __DIR__ . "/entities/$name.php";
    if (is_readable($fn)) {
        require_once $fn;
    }
});

return $helperSet;
