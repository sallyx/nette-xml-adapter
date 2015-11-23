<?php

/**
 * Test: Sallyx\Nette\DI\Config\Adapters\XmlAdapter
 */

use Nette\DI\Config;
use Nette\DI\Statement;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.xml');


$config = new Config\Loader;
$config->addAdapter('xml','Sallyx\Nette\DI\Config\Adapters\XmlAdapter');

$data = $config->load('files/xmlAdapter.entity.xml');
Assert::equal(array(
	new Statement('ent', array(1)),
	new Statement(array(
			new Statement('ent', array(2)),
			'inner',
		),
		array('3', '4')
	),
	new Statement(array(
			new Statement('ent', array('3')),
			'inner',
		),
		array('5','6')
	),
), $data);

$data = $config->load('files/xmlAdapter.entity.xml');
$config->save($data, TEMP_FILE);
$actual = file_get_contents(TEMP_FILE);
$actual = preg_replace('/\<([^\s\/>]+)(\s*[^\/>]*)\/\s*\>/i', '<$1$2></$1>', $actual);
Assert::match(<<<EOD
<?xml version="1.0"?>
<config xmlns:nc="http://www.sallyx.org/xmlns/nette/config/1.0" xmlns="http://www.sallyx.org/xmlns/nette/config/1.0" array="numeric"><item statement="statement"><s><ent>ent</ent><args array="numeric"><item number="1"></item></args></s></item><item statement="statement"><s><ent>ent</ent><args array="numeric"><item number="2"></item></args></s><s><ent>inner</ent><args array="numeric"><item>3</item><item>4</item></args></s></item><item statement="statement"><s><ent>ent</ent><args array="numeric"><item>3</item></args></s><s><ent>inner</ent><args array="numeric"><item>5</item><item>6</item></args></s></item></config>
EOD
, $actual);
