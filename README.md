# Input-Output processor

The libarary for making application of import and export data.
It's expected to bring to your life the ability of inserting and transforming any kind of data with fast and flexibility.
The data input can be a file data (csv, json ...) output is a database (mysql, mongodb ...), you are able to define a variety of inputs and outputs.

Currenly, the library only support CSV and CommerceTools as input and output as it's still being developed, if you find it does not fit for your application, you can write your own inputs and outputs, that IOs just have to follow the NodeIoInterface which has two basic abstract methods (read and write). I'd very appreciate for your contributing and telling us what feature you love to have in this libaray. 

I hope this can be a helful for you.

## Basic Usage

```php
<?php

use Shopmacher\IEProcessor\CommerceTools\CommerceToolsProductIo;
use Shopmacher\IEProcessor\Csv\CsvDataIo;

// define CSV as input
$reader = new CsvDataIo(';');
// give the instruction for CsvDataIo to build flat data from csv rows to structure data
// then that data can be easily inserted or transformed to another kind of data
$reader->setForwardMap([
	'product' => [
		'nid' => %sku%,
		'name' => [
			'en' => %product_name_en%,
			'de' => %product_name_de%
		],
		'slug' => [
			'en' => %product_slug_en%,
			'de' => %product_slug_de%
		]
	]
]);

$productNodes  = $reader->read(file_get_contents($csv_file));

// define Commercetools as output
$writer = new CommerceToolsProductIo($client);
$writer->write($productNodes);

```


### Requirements

- IEProcessor works with PHP 7.0 or above.


