<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu;
use CeusMedia\Bootstrap\Nav\Pills as NavPills;
use CeusMedia\Bootstrap\Link;

$dropdown	= new DropdownMenu();
$dropdown->addLink( new Link( "#pill-2-0", "Link 1" ) );

$component	= new NavPills();
$component->add( "#pill-0", "Pill 1", NULL, "file" );
$component->addLink( new Link( "#pill-1", "Pill 2", NULL, "file" ) );
$component->addDropdown( $dropdown, "Pill 3", NULL, "folder-close", "folder-open" );
$component->setActive( 2 );

print '<h3>Nav: Pills</h3>'.$component;
