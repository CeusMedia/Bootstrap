<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

//namespace CeusMedia\Bootstrap;
//use \CeusMedia\Bootstrap;

error_reporting( E_ALL );
ini_set( 'display_errors', TRUE );
ob_start();

print '<h1 class="muted">CeusMedia Component Demo</h1>';
print '<h2>Bootstrap</h2>';

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
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Button 1", "btn-danger", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Button 2", "btn-warning", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Button 3", "btn-success", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Link( "#", "Button 4", "btn-info", "star" ) );
$component->add( new CeusMedia\Bootstrap\Button\Submit( "save", "Button 5", "btn-primary", "star" ) );
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

$page	= new UI_HTML_PageFrame();
$page->addStylesheet( "https://cdn.ceusmedia.de/css/bootstrap.min.css" );
$page->addJavaScript( "https://cdn.ceusmedia.de/js/jquery/1.10.2.min.js" );
$page->addJavaScript( "https://cdn.ceusmedia.de/js/bootstrap.min.js" );
$page->addBody( '<div class="container">'.ob_get_clean().'</div>' );

print $page->build();
