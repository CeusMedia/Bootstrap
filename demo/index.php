<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
//namespace CeusMedia\Bootstrap;
//use \CeusMedia\Bootstrap;

use \UI_HTML_Tag as Tag;
use \UI_HTML_Elements as Elements;

use \Net_HTTP_Request_Receiver as Request;
new \UI_DevOutput();

error_reporting( E_ALL );
ini_set( 'display_errors', TRUE );

$versions	= array(
	'2.3.2',
	'4.4.1',
);

$request	= new Request();

$version	= "2.3.2";
if( $request->get( 'version' ) && in_array( $request->get( 'version' ), $versions ) )
	$version	= $request->get( 'version' );
$isBs4	= version_compare( $version, 4, '>=' );
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
	Tag::create( 'div', array(
		Tag::create( 'h1', '<span class="muted">CeusMedia</span> Component Demo', array( 'class' => 'muted text-muted display-4' ) ),
		Tag::create( 'h2', 'Bootstrap' ),
	), array( 'class' => 'not-bs2-hero-unit not-bs4-jumbotron' ) ),
	Tag::create( 'form', array(
		Tag::create( 'div', array(
			Tag::create( 'div', array(
				Tag::create( 'select', Elements::Options( array_combine( $versions, $versions ), $version ), array(
					'name'		=> 'version',
					'class' 	=> 'bs2-span12 bs4-form-control',
					'onchange'	=> 'this.form.submit()',
				) ),
			), array( 'class' => 'bs2-span3 bs4-form-group bs4-col-md-3' ) ),
		), array( 'class' => 'bs2-row-fluid bs4-form-row' ) ),
	), array( 'action' => './', 'method' => 'GET' ) ),
	join( $contents ),
], ['class' => 'container'] );


$cdnBaseUrl	= 'https://cdn.ceusmedia.de/';
//$cdnBaseUrl	= 'http://localhost/lib/GitHub/CeusMedia/AssetLibrary';

$page		= new UI_HTML_PageFrame();
$page->addBody( BootstrapVersionProcessor::process( $body, $version ) );
$page->addJavaScript( $cdnBaseUrl.'js/jquery/1.10.2.min.js' );
if( $isBs4 ){
	$page->addHead( Tag::create( 'link', NULL, array( 'rel' => 'stylesheet', 'href' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', 'crossorigin' => 'anonymous' ) ) );
	$page->addHead( Tag::create( 'script', '', array( 'src' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js', 'crossorigin' => 'anonymous' ) ) );
}
else {
	$page->addStylesheet( $cdnBaseUrl.'css/bootstrap.min.css' );
	$page->addJavaScript( $cdnBaseUrl.'js/bootstrap.min.js' );
}
$page->addStylesheet( $cdnBaseUrl.'fonts/FontAwesome/font-awesome.min.css' );
$page->addStylesheet( 'style.css' );

print $page->build( array(
	'class'		=> join( ' ', array(
		'bs-'.BootstrapVersionProcessor::getMajorVersion( $version ),
	) ),
) );


class BootstrapVersionProcessor
{
	static public function getMajorVersion( $version ): string
	{
		$versionParts	= explode( '.', $version );
		return (int) array_shift( $versionParts );
	}

	static public function process( $content, $version ): string
	{
		$majorVersion	= self::getMajorVersion( $version );
		$cssPrefix		= 'bs'.$majorVersion.'-';
		if( substr_count( $content, $cssPrefix ) ){
			while( preg_match( '/ class="[^"]*'.$cssPrefix.'/', $content ) ){
				$pattern	= '/(class=")([^"]*)?('.$cssPrefix.')([^ "]+)([^"]*)(")/';
				$content	= preg_replace( $pattern, '\\1\\2\\4\\5\\6', $content );
			}
			$otherVersions	= array_diff( array( 2, 3, 4 ), array( $majorVersion ) );
			foreach( $otherVersions as $version ){
				$pattern	= '/(class=")([^"]*)(bs'.$version.'-[^ "]+)([^"]*)(")/';
				$content	= preg_replace( $pattern, '\\1\\2\\4\\5', $content );
			}
			$content	= preg_replace( '/(class=")\s*([^ ]*)\s*(")/', '\\1\\2\\3', $content );
			$content	= preg_replace( '/ class=""/', '', $content );
		}
		return $content;
	}
}
