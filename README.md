[![GitHub license](https://img.shields.io/github/license/fawno/QPDF)](https://github.com/fawno/QPDF/blob/master/LICENSE)
[![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/fawno/QPDF)](https://github.com/fawno/QPDF/tags)
[![Packagist](https://img.shields.io/packagist/v/fawno/qpdf)](https://packagist.org/packages/fawno/qpdf)
[![Packagist Downloads](https://img.shields.io/packagist/dt/fawno/qpdf)](https://packagist.org/packages/fawno/qpdf/stats)
[![GitHub issues](https://img.shields.io/github/issues/fawno/QPDF)](https://github.com/fawno/QPDF/issues)
[![GitHub forks](https://img.shields.io/github/forks/fawno/QPDF)](https://github.com/fawno/QPDF/network)
[![GitHub stars](https://img.shields.io/github/stars/fawno/QPDF)](https://github.com/fawno/QPDF/stargazers)

# QPDF

PHP class for access QPDF C Interface

## Requirements
The [QPDF](https://github.com/qpdf/qpdf) libary (dll or so).

## Instalation

```sh
php composer.phar require "fawno/qpdf"
```

```php
<?php
  require 'vendor/autoload.php';

  use Fawno\QPDF\QPDF;
```

## Example

```php
	$lib_path = __DIR__ . '/qpdf28.dll';
	$qpdf = new QPDF($lib_path);

	$filename = __DIR__ . '/encrypted_document.pdf';
	$password = 'secret_password';

	$qpdf->readFile($filename, $password);
	if ($qpdf->hasError()) {
		$error = $qpdf->getError();
		print_r($error);
		die();
	}

	if ($qpdf->hasWarning()) {
		$warning = $qpdf->getWarning();
		print_r($warning);
	}

	$filename = __DIR__ . '/document_without_encrypt.pdf';
	$qpdf->initWrite($filename);
	if ($qpdf->hasError()) {
		$error = $qpdf->getError();
		print_r($error);
		die();
	}

	$qpdf->preserveEncryption(false);

	$qpdf->write();
	if ($qpdf->hasError()) {
		$error = $qpdf->getError();
		print_r($error);
		die();
	}
```
