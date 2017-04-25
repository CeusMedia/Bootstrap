<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

//namespace CeusMedia\Bootstrap;
//use \CeusMedia\Bootstrap;

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

$page	= new UI_HTML_PageFrame();
$page->addStylesheet( "http://cdn.int1a.net/css/bootstrap.min.css" );
$page->addJavaScript( "http://cdn.int1a.net/js/jquery/1.10.2.min.js" );
$page->addJavaScript( "http://cdn.int1a.net/js/bootstrap.min.js" );
$page->addBody( '<div class="container">'.ob_get_clean().'</div>' );

print $page->build();

