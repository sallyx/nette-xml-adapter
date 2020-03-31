<?php

/**
 * Test: Sallyx\Nette\Adapters\XmlAdapter
 */

use Sallyx\Nette\Configurator;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.xml');


$config = new Configurator;
$config->setTempDirectory(TEMP_DIR);
$config->addAdapter('xml','Sallyx\\Nette\\DI\\Config\\Adapters\\XmlAdapter');

$data = @$config->addConfig(__DIR__.'/files/xmlAdapter.xml', 'development');
$container = $config->createContainer();
Assert::type('Nette\\DI\\Container', $container);
Assert::same('petr@localhost',$container->parameters['webmasterEmail']);
