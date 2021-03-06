<?php

/**
 * Test: Sallyx\Nette\DI\Config\Adapters\XmlAdapter errors.
 */

use Nette\DI\Config;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$config = new Config\Loader;
$config->addAdapter('xml', 'Sallyx\\Nette\\DI\\Config\\Adapters\\XmlAdapter');

Assert::exception(function () use ($config) {
	$config->load('files/xmlAdapter.error1.xml');
}, 'Nette\InvalidStateException', "Duplicated key 'scalar'.");

Assert::exception(function () use ($config) {
	$config->load('files/xmlAdapter.error2.xml');
}, 'Nette\InvalidStateException', "Replacing operator is available only for arrays, element 'test' is not array");

Assert::exception(function () use ($config) {
	$config->load('files/xmlAdapter.error3.xml');
}, 'Nette\InvalidStateException', 'Expected <ent> element in statement <factory statement="statement"><s>DateTime<args><a>0</a></args></s></factory>');

Assert::exception(function () use ($config) {
	$config->load('files/xmlAdapter.error4.xml');
}, 'Nette\InvalidStateException', 'Element <ent> must have a non-empty string value.');

Assert::exception(function () use ($config) {
	$config->load('files/xmlAdapter.error5.xml');
}, 'Nette\InvalidStateException', "Attribute space has an unknown value 'perverse'");

Assert::exception(function () use ($config) {
	$config->load('files/xmlAdapter.error6.xml');
}, 'Nette\InvalidStateException', 'Element with attribute array="string" can\'t have children: <test array="string"><child>a,b,c</child></test>');
