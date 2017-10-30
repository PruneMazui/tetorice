<?php
require_once __DIR__ . '/../vendor/autoload.php';

$reflection = new \ReflectionClass(\PHPUnit_Framework_Assert::class);
require_once dirname($reflection->getFileName()) . '/Assert/Functions.php';
