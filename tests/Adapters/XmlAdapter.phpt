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
