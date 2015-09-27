<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
require dirname(__DIR__) . '/config/bootstrap.php';

use Cake\Core\Configure;

/**
 * Global constant that is true only when Unit Tests are being run.
 * Allows for code blocks that run only during testing or vice versa.
 */
Configure::write('PHPUNIT', true);