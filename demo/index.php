<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

//namespace CeusMedia\Bootstrap;
//use \CeusMedia\Bootstrap;

use \UI_HTML_Tag as Tag;
use \UI_HTML_Elements as Elements;

use \Net_HTTP_Request_Receiver as Request;

new \UI_DevOutput();
$versions	= array(
	'2.3.2',
	'4.4.1',
);

$version	= "2.3.2";
//$version	= "4.4.1";

$request	= new Request();
if( $request->get( 'version' ) && in_array( $request->get( 'version' ), $versions ) )
	$version	= $request->get( 'version' );

CeusMedia\Bootstrap\Base\Component::$defaultBsVersion	= $version;
CeusMedia\Bootstrap\Base\Structure::$defaultBsVersion	= $version;

error_reporting( E_ALL );
ini_set( 'display_errors', TRUE );

ob_start();

print Tag::create( 'div', array(
	Tag::create( 'h1', 'CeusMedia Component Demo', array( 'class' => 'muted' ) ),
	Tag::create( 'h2', 'Bootstrap', array() ),
), array( 'class' => 'not-bs2-hero-unit not-bs4-jumbotron' ) );

print Tag::create( 'form', array(
	Tag::create( 'div', array(
		Tag::create( 'div', array(
			Tag::create( 'select', Elements::Options( array_combine( $versions, $versions ), $version ), array(
				'name'		=> 'version',
				'class' 	=> 'bs2-span12 bs4-form-control',
				'onchange'	=> 'this.form.submit()',
			) ),
		), array( 'class' => 'bs2-span3 bs4-form-group bs4-col-md-3' ) ),
	), array( 'class' => 'bs2-row-fluid bs4-form-row' ) ),
), array( 'action' => './', 'method' => 'GET' ) );

CeusMedia\Bootstrap\Icon::$defaultSet	= 'fontawesome';

$component	= new CeusMedia\Bootstrap\Breadcrumbs();
$component->addLink( new CeusMedia\Bootstrap\Link( "#", "CeusMedia", NULL, "folder-open" ) );
$component->addLink( new CeusMedia\Bootstrap\Link( "#", "Bootstrap", NULL, "folder-open" ) );
$component->add( "Demo", NULL, NULL, "file" );
print '<h3>Breadcrumbs</h3>'.$component;

$dropdown0	= new \CeusMedia\Bootstrap\Dropdown();
$dropdown0->addLink( new \CeusMedia\Bootstrap\Link( "#action-0-0", "Link 1" ) );
$dropdown1	= new \CeusMedia\Bootstrap\Dropdown();
$dropdown1->addLink( new \CeusMedia\Bootstrap\Link( "#action-0-0-0", "Link 1-1" ) );
$dropdown0->addDropdown( "Menu 1", $dropdown1 );
$component	= new \CeusMedia\Bootstrap\Dropdown\Button( "Dropdown-Button", $dropdown0, "btn-info", "star" );
print '<h3>DropdownButton</h3>'.$component;
print new CeusMedia\Bootstrap\Code( '
$dropdown	= new \CeusMedia\Bootstrap\Dropdown();
$dropdown->add( new \CeusMedia\Bootstrap\Link( "#", "Link 1" ) );
$component	= new \CeusMedia\Bootstrap\Dropdown\Button( "Dropdown-Button", $dropdown, "btn-info", "star" );
' );


$navbar	= new CeusMedia\Bootstrap\TabbableNavbar();
$navbar->setBrand( "123", "#" );
$navbar->add( "tab-0-0", "Tab 1", "Content 1" );
$navbar->add( "tab-0-1", "Tab 2", "Content 2" );
print '<h3>TabbableNavbar</h3>'.$navbar;


$component	= new CeusMedia\Bootstrap\Tabs( "tabs1" );
$component->add( "tab-1-0", "#tab-1-0", "Tab 1", "Content 1" );
$component->add( "tab-1-1", "#tab-1-1", "Tab 2", "Content 2" );
print '<h3>Tabs</h3>'.$component;


$dropdown	= new CeusMedia\Bootstrap\Dropdown();
$dropdown->addLink( new CeusMedia\Bootstrap\Link( "#pill-2-0", "Link 1" ) );
$component	= new CeusMedia\Bootstrap\Nav\Pills();
$component->add( "#pill-0", "Pill 1", NULL, "file" );
$component->addLink( new CeusMedia\Bootstrap\Link( "#pill-1", "Pill 2", NULL, "file" ) );
$component->addDropdown( $dropdown, "Pill 3", NULL, "folder-close", "folder-open" );
$component->setActive( 2 );
print '<h3>Nav: Pills</h3>'.$component;


$component	= new CeusMedia\Bootstrap\Button\Group();
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Danger", "btn-danger", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Warning", "btn-warning", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Success", "btn-success", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Info", "btn-info", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Submit( "save", "Primary", "btn-primary", "star" ) );

if( version_compare( $version, 4, '>=' ) )
	$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Secondary", "btn-secondary", "star" ) );
if( version_compare( $version, 4, '>=' ) ){
	$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Light", "btn-light", "star" ) );
	$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Dark", "btn-dark", "star" ) );
}
else
	$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Button 6", "btn-inverse", "star" ) );
print '<h3>Button Group</h3>'.$component;

$component	= new CeusMedia\Bootstrap\Badge( "2", CeusMedia\Bootstrap\Badge::CLASS_INFO );
print '<h3>Badge</h3>'.$component;
print new CeusMedia\Bootstrap\Code( '
$component	= new CeusMedia\Bootstrap\Badge( "2", CeusMedia\Bootstrap\Badge::CLASS_INFO );
' );

$component	= new CeusMedia\Bootstrap\PageControl( "#page-", 0, 10 );
$component->patternUrl	= "%s";
print '<h3>PageControl</h3>'.$component;

$modal		= new CeusMedia\Bootstrap\Modal( "modal-id" );
$modal->setHeading( 'Demo Modal Heading' );
$modal->setBody( "<h4>Hello World!</h4><p>Lorem ipsum ...</p>" );
$modal->setCloseButtonClass( 'btn btn-small' );
$modal->setCloseButtonIconClass( 'icon-remove' );
$modal->setCloseButtonLabel( 'dismiss' );
$modal->setSubmitButtonClass( 'btn btn-primary' );
$modal->setSubmitButtonIconClass( 'icon-arrow-right' );
$modal->setSubmitButtonLabel( 'continue' );
$modalTrigger	= new CeusMedia\Bootstrap\Modal\Trigger( "modal-id", "open" );

print '<h3>Modal</h3>'.$modalTrigger->render().$modal->render();
print '<br/>';
print '<br/>';

$content	= ob_get_clean();
$content	= BootstrapVersionProcessor::process( $content, $version );

$page	= new UI_HTML_PageFrame();
$page->addStylesheet( 'https://cdn.ceusmedia.de/css/bootstrap/'.$version.'/bootstrap.min.css' );
$page->addStylesheet( 'https://cdn.ceusmedia.de/fonts/FontAwesome/font-awesome.min.css' );
$page->addStylesheet( 'style.css' );
$page->addJavaScript( 'https://cdn.ceusmedia.de/js/jquery/1.10.2.min.js' );
$page->addJavaScript( 'https://cdn.ceusmedia.de/js/bootstrap/'.$version.'/bootstrap.min.js' );
$page->addBody( '<div class="container">'.$content.'</div>' );

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
