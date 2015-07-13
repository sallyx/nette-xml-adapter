<?php

/**
 * Test: Sallyx\Nette\DI\Config\Adapters\XmlAdapter
 */

use Sallyx\Nette\DI\Config\Adapters\XmlAdapter;
use Nette\DI\Statement;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$adapter = new XmlAdapter;
$data = $adapter->load('files/xmlAdapter.services.xml');

Assert::equal(
	array(
		new Statement('Class', array(
			'arg1',
			new Nette\DI\Statement('Class2', array('arg2', 'arg3')),
		)),
		new Statement('Class', array(
			'arg1',
			new Nette\DI\Statement('Class2', array('arg2', 'arg3')),
		)),
	),
	$data
);
