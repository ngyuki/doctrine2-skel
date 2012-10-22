<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;

require_once __DIR__ . '/vendor/autoload.php';

$isDevMode = true;
$config = Setup::createXMLMetadataConfiguration(array(__DIR__ . '/xml'), $isDevMode);

$params = include __DIR__ . '/db-params.php';

$em = EntityManager::create($params, $config);
$db = $em->getConnection();

$helperSet = new HelperSet(array(
	'em' => new EntityManagerHelper($em),
	'db' => new ConnectionHelper($db),
	'dialog' => new DialogHelper(),
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
