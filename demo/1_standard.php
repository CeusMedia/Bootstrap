<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Common\Net\HTTP\Request\Receiver as Request;

(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');
require __DIR__.'/VersionProcessor.php';
require __DIR__.'/DemoAppTemplate.php';

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

new CeusMedia\Common\UI\DevOutput();

$versions	= [
	'2.3.2',
	'4.4.1',
	'5.3.3',
];

$request	= new Request();

$version	= "2.3.2";
if( '' !== $request->get( 'version', '' ) && in_array( $request->get( 'version' ), $versions, TRUE ) )
	/** @var string $version */
	$version	= $request->get( 'version' );
$isBs5	= version_compare( $version, '5', '>=' );
$isBs4	= !$isBs5 && version_compare( $version, '4', '>=' );
CeusMedia\Bootstrap\Base\Element::$defaultBsVersion		= $version;
CeusMedia\Bootstrap\Base\Structure::$defaultBsVersion	= $version;
CeusMedia\Bootstrap\Icon::$defaultSet	= 'fontawesome';

$parts	= [
#	'link',
	'alert',
	'breadcrumbs',
	'progress',
	'button',
	'buttongroup',
	'dropdown',
	'modal',
	'nav_tabs',
	'nav_pills',
	'nav_list',
	'badge',
//	'pagination',
	'pagecontrol',
	'navbar_tabbable',
];

$contents	= [];
foreach( $parts as $part ){
	ob_start();
	if( file_exists( 'parts/'.$part.'.php' ) )
		include_once 'parts/'.$part.'.php';
	$contents[]	= ob_get_clean().'<hr/>';
}
$content	= join( $contents );

$a = new DemoAppTemplate( 'Component Demo', './1_standard.php' );
$a->setBootstrapVersion( 5 );
$a->setFontAwesomeVersion( 6 );
$a->setContent( $content );
$a->run();
