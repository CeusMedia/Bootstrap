<?php
use CeusMedia\Bootstrap\Nav\Tabs;

$component	= new Tabs( "tabs1" );
$component->add( "tab-1-0", "#tab-1-0", "Tab 1", "Content 1" );
$component->add( "tab-1-1", "#tab-1-1", "Tab 2", "Content 2" );

print '<h3>Tabs</h3>'.$component;
