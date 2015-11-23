<?php

/**
 * Test: Sallyx\Nette\DI\Config\Adapters\XmlAdapter
 */

use Nette\DI\Config;
use Nette\DI\Statement;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$config = new Config\Loader;
$config->addAdapter('xml','Sallyx\Nette\DI\Config\Adapters\XmlAdapter');
$data = $config->load('files/xmlAdapter.merge.xml', 'production');
Assert::same(array(
	'webname' => 'the example',
	'extensions' => array(
		'redis' => 'Kdyby\\Redis\\DI\\RedisExtension',
		'streamWrappers' => 'Sallyx\\Bridges\\StreamWrappers\\Nette\\DI\\StreamWrappersExtension',
	),
	'redis' => array(
		'journal' => TRUE,
		'storage' => TRUE,
		'session' => array('native' => FALSE, 'debugger' => TRUE),
	),
), $data);

$data = $config->load('files/xmlAdapter.merge2.xml', 'development');
Assert::same(array(
	'webname' => 'the example',
	'extensions' => array(
	),
	'redis' => array(
	),
), $data);
