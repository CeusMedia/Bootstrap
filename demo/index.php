<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Common\Net\HTTP\Request\Receiver as Request;
use CeusMedia\Common\UI\HTML\Elements as Elements;
use CeusMedia\Common\UI\HTML\PageFrame;
use CeusMedia\Common\UI\HTML\Tag as Tag;

(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
//namespace CeusMedia\Bootstrap;
//use \CeusMedia\Bootstrap;

new CeusMedia\Common\UI\DevOutput();

error_reporting( E_ALL );
ini_set( 'display_errors', TRUE );

$versions	= [
	'2.3.2',
	'4.4.1',
];

$request	= new Request();

$version	= "2.3.2";
if( $request->get( 'version' ) && in_array( $request->get( 'version' ), $versions ) )
	/** @var string $version */
	$version	= $request->get( 'version' );
$isBs4	= version_compare( $version, '4', '>=' );
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
	'badge',
//	'pagination',
	'pagecontrol',
	'navbar_tabbable',
];
foreach( $parts as $part ){
	ob_start();
	if( file_exists( 'parts/'.$part.'.php' ) )
		include_once 'parts/'.$part.'.php';
	$contents[]	= '<hr/>'.ob_get_clean();
}


$body	= Tag::create( 'div', [
	Tag::create( 'div', [
		Tag::create( 'h1', '<span class="muted">CeusMedia</span> Component Demo', ['class' => 'muted text-muted display-4'] ),
		Tag::create( 'h2', 'Bootstrap' ),
	], ['class' => 'not-bs2-hero-unit not-bs4-jumbotron'] ),
	Tag::create( 'form', [
		Tag::create( 'div', [
			Tag::create( 'div', [
				Tag::create( 'select', Elements::Options( array_combine( $versions, $versions ), $version ), [
					'name'		=> 'version',
					'class' 	=> 'bs2-span12 bs4-form-control',
					'onchange'	=> 'this.form.submit()',
				] ),
			], ['class' => 'bs2-span3 bs4-form-group bs4-col-md-3'] ),
		], ['class' => 'bs2-row-fluid bs4-form-row'] ),
	], ['action' => './', 'method' => 'GET'] ),
	join( $contents ),
], ['class' => 'container'] );


$cdnBaseUrl	= 'https://cdn.ceusmedia.de/';
//$cdnBaseUrl	= 'https://localhost/lib/GitHub/CeusMedia/AssetLibrary';

$page		= new PageFrame();
$page->addBody( BootstrapVersionProcessor::process( $body, $version ) );
$page->addJavaScript( $cdnBaseUrl.'js/jquery/1.10.2.min.js' );
if( $isBs4 ){
	$page->addHead( Tag::create( 'link', NULL, [
		'rel'			=> 'stylesheet',
		'href'			=> 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css',
		'crossorigin'	=> 'anonymous'
	] ) );
	$page->addHead( Tag::create( 'script', '', [
		'src'			=> 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js',
		'crossorigin'	=> 'anonymous'
	] ) );
}
else {
	$page->addStylesheet( $cdnBaseUrl.'css/bootstrap.min.css' );
	$page->addJavaScript( $cdnBaseUrl.'js/bootstrap.min.js' );
}
$page->addStylesheet( $cdnBaseUrl.'fonts/FontAwesome/font-awesome.min.css' );
$page->addStylesheet( 'style.css' );

print $page->build( [
	'class'		=> join( ' ', [
		'bs-'.BootstrapVersionProcessor::getMajorVersion( $version ),
	] ),
] );


class BootstrapVersionProcessor
{
	public static function getMajorVersion( string $version ): int
	{
		$versionParts	= explode( '.', $version );
		return (int) array_shift( $versionParts );
	}

	public static function process( string $content, string $version ): string
	{
		$majorVersion	= self::getMajorVersion( $version );
		$cssPrefix		= 'bs'.$majorVersion.'-';
		if( 0 !== substr_count( $content, $cssPrefix ) ){
			while( 0 !== preg_match( '/ class="[^"]*'.$cssPrefix.'/', $content ) ){
				$pattern	= '/(class=")([^"]*)?('.$cssPrefix.')([^ "]+)([^"]*)(")/';
				/** @var string $content */
				$content	= preg_replace( $pattern, '\\1\\2\\4\\5\\6', $content );
			}
			$otherVersions	= array_diff( [2, 3, 4], [$majorVersion] );
			foreach( $otherVersions as $version ){
				$pattern	= '/(class=")([^"]*)(bs'.$version.'-[^ "]+)([^"]*)(")/';
				/** @var string $content */
				$content	= preg_replace( $pattern, '\\1\\2\\4\\5', $content );
			}
			/** @var string $content */
			$content	= preg_replace( '/(class=")\s*([^ ]*)\s*(")/', '\\1\\2\\3', $content );
			/** @var string $content */
			$content	= preg_replace( '/ class=""/', '', $content );
		}
		return $content;
	}
}
