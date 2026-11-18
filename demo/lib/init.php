<?php
declare(strict_types=1);

/**
 *	Common setup of all demos: autoloader, error display and demo helpers.
 *	Demos are served from folder "demo", which is the working directory.
 */

(@include __DIR__.'/../../vendor/autoload.php') or die('Please use composer to install required packages.');

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

require_once __DIR__.'/VersionProcessor.php';
require_once __DIR__.'/DemoAppTemplate.php';
require_once __DIR__.'/DemoExample.php';
require_once __DIR__.'/RadioBar.php';
