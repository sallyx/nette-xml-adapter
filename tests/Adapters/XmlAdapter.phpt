<?php

/**
 * Test: Sallyx\Nette\Adapters\XmlAdapter
 */

use Nette\DI\Config;
use Nette\DI\Statement;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.xml');


$config = new Config\Loader;
$config->addAdapter('xml','Sallyx\Nette\Adapters\XmlAdapter');

$data = $config->load('files/xmlAdapter.xml', 'production');
Assert::same(array(
	'webname' => 'the example',
	'database' => array(
		'adapter' => 'pdo_mysql',
		'params' => array(
			'host' => 'db.example.com',
			'username' => 'dbuser',
			'password' => 'secret ',
			'dbname' => 'dbname',
		),
	),
), $data);


$data = $config->load('files/xmlAdapter.xml', 'development');
Assert::same(array(
	'webname' => 'the example',
	'database' => array(
		'adapter' => 'pdo_mysql',
		'params' => array(
			'host' => 'dev.example.com',
			'username' => 'devuser',
			'password' => 'devsecret',
			'dbname' => 'dbname',
		),
	),
	'timeout' => 10,
	'display_errors' => TRUE,
	'html_errors' => FALSE,
	'items' => array(10, 20),
	'php' => array(
		'zlib.output_compression' => TRUE,
		'date.timezone' => 'Europe/Prague',
	),
), $data);


$config->save($data, TEMP_FILE);
$actual = file_get_contents(TEMP_FILE);
$actual = preg_replace('/\<([^\s\/>]+)(\s*[^\/>]*)\/\s*\>/i', '<$1$2></$1>', $actual);
Assert::match(<<<EOD
<?xml version="1.0"?>
<config xmlns:nc="http://www.sallyx.org/xmlns/nette/config/1.0" xmlns="http://www.sallyx.org/xmlns/nette/config/1.0"><webname>the example</webname><database><adapter>pdo_mysql</adapter><params><host>dev.example.com</host><username>devuser</username><password>devsecret</password><dbname>dbname</dbname></params></database><timeout number="10"></timeout><display_errors bool="1"></display_errors><html_errors bool="0"></html_errors><items array="numeric"><item number="10"></item><item number="20"></item></items><php><zlib.output_compression bool="1"></zlib.output_compression><date.timezone>Europe/Prague</date.timezone></php></config>
EOD
, $actual);


$data = $config->load('files/xmlAdapter.xml');
$config->save($data, TEMP_FILE);
$actual = file_get_contents(TEMP_FILE);
$actual = preg_replace('/\<([^\s\/>]+)(\s*[^\/>]*)\/\s*\>/i', '<$1$2></$1>', $actual);
Assert::match(<<<EOD
<?xml version="1.0"?>
<config xmlns:nc="http://www.sallyx.org/xmlns/nette/config/1.0" xmlns="http://www.sallyx.org/xmlns/nette/config/1.0"><production><webname>the example</webname><database><adapter>pdo_mysql</adapter><params><host>db.example.com</host><username>dbuser</username><password>secret </password><dbname>dbname</dbname></params></database></production><development extends="production"><database><params><host>dev.example.com</host><username>devuser</username><password>devsecret</password></params></database><timeout number="10"></timeout><display_errors bool="1"></display_errors><html_errors bool="0"></html_errors><items array="numeric"><item number="10"></item><item number="20"></item></items><php><zlib.output_compression bool="1"></zlib.output_compression><date.timezone>Europe/Prague</date.timezone></php></development><nothing></nothing></config>
EOD
, $actual);

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
