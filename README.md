# Xml Adapter

[![Build Status](https://travis-ci.org/sallyx/nette-xml-adapter.svg?branch=master)](https://travis-ci.org/sallyx/nette-xml-adapter)

XML adapter could be used to write configuration in XML.
Thanks to namespace usage (http://www.sallyx.org/xmlns/nette/config/1.0) you can have
configuration common for your php application (nette) and other application
in the same xml file.

XmlAdapter reads xml file and convert it to PHP array.

See xml example here: [tests/Adapters/files/xmlAdapter.xml](tests/Adapters/files/xmlAdapter.xml)

## Supported types

### Associative array

```xml
<myarray><key1>value1</key1><key2>value2</key2></myarray>
```
Becomes:
```php
[key1 => 'value1', key2=>'value2']
```
### Numeric array

```xml
<myarray array="numeric"><key>value1</key><key>value2</key></myarray>
```
Becomes (*element names are ignored here*):
```php
[0=>'value1',1=>'value2]
```
### String, Null, Number, Boolean

```xml
<myarray array="numeric">
<x> trimmed string </x>
<x space="preserve"> string with spaces  </x>
<x>1</x>
<x number="2"/>
<x bool="yes"/>
<x null="null" />
</myarray>
```
Becomes:
```php
["trimmed string", " string with spaces  ","1", 2, TRUE, NULL]
```
*Bool support "yes", "true", "on" and "1"*
### Statement
```xml
<factory statement="statement">
    <s><ent>DateTime</ent><args><a numeric="0" /></args></s>
   <s><ent>format</ent><args><a>%B</a></args></s>
</factory>
```
Is equivalent to neon: ```DateTime(0)::format("%B")```

## Syntactic sugar

### Array from string
```xml
<myarray array="string">1,2,3</myarray>
```
Becomes:
```php
["1", "2", "3"]
```
#### The same with custom delimiter:
```xml
<myarray array="string" delimiter=";">1;2;3</myarray>
```

### Statement
Of course you can use `array from string` as statement arguments.
```xml
<xxx statement="statement">
<s><ent>fooo</ent><args array="string" delimiter=";">1;2;3</args></s>
</xxx>
```
If there is only one argument, you dont need to use element inside args element.
These statements are equivalent:
```xml
<xxx statement="statement"><s><ent>fooo</ent><args><a>1</a></args></s></xxx>
<xxx statement="statement"><s><ent>fooo</ent><args>1</args></s></xxx>
```

You can use neon syntax for statement:
```xml
<xxx statement="DateTime(0)::format('%B')" />
```

#Usage

## Install with composer

```
composer require sallyx/nette-xml-adapter
```

## Test

```php
require 'vendor/autoload.php';
use Sallyx\Nette\DI\Config\Adapters\XmlAdapter;

$adapter = new XmlAdapter;
$file = 'vendor/sallyx/nette-xml-adapter/tests/Adapters/files/xmlAdapter.xml';
$config = $adapter->load($file);
print_r($config);
```

## Usage with Nette

Add this line into your app/bootstrap.php after ```$configurator = new Nette\Configurator;```

```
$xmlAdapter = new Sallyx\Nette\DI\Config\Adapters\XmlAdapter;
$configurator->addAdapter('xml', $xmlAdapter);
```

And now you can load XML configuration files (alongside with neon files).
For example:

```
$configurator->addConfig(__DIR__ . '/config/config.xml', Nette\Config\Configurator::AUTO);
```

### How to convert neon config file to xml config file

```php
require __DIR__ . '/vendor/autoload.php';
use Nette\DI\Config\Adapters\NeonAdapter;
use Sallyx\Nette\DI\Config\Adapters\XmlAdapter;

$na = new NeonAdapter;
$xa = new XmlAdapter;
$config = $na->load('config.neon');
$xmlConfig = $xa->dump($config);
// pretty output
$domxml = new DOMDocument('1.0');
$domxml->preserveWhiteSpace = false;
$domxml->formatOutput = true;
$domxml->loadXML($xmlConfig);
echo $domxml->saveXML();
```
