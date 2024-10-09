<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Nav\TabbableNavbar;

$navbar	= new TabbableNavbar();
$navbar->setBrand( "123", "#" );
$navbar->add( "tab-0-0", "Tab 1", "Content 1" );
$navbar->add( "tab-0-1", "Tab 2", "Content 2" );

print '<h3>TabbableNavbar</h3>'.$navbar;
