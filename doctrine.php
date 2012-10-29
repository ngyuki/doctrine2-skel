<?php
require_once __DIR__ . '/vendor/autoload.php';

$isDevMode = true;
$config = \Doctrine\ORM\Tools\Setup::createXMLMetadataConfiguration(array(__DIR__ . '/xml'), $isDevMode);

$params = include __DIR__ . '/db-params.php';

$em = \Doctrine\ORM\EntityManager::create($params, $config);
$db = $em->getConnection();

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
	'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
	'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($db),
	'dialog' => new \Symfony\Component\Console\Helper\DialogHelper(),
));

spl_autoload_register(function($name){
	
	$fn = __DIR__ . "/entities/$name.php";
	
	if (is_readable($fn))
	{
		require_once $fn;
	}
});

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet, array(
	// Migrations Commands
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
));
