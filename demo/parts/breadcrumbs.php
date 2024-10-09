<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Nav\Breadcrumbs;

$component	= new Breadcrumbs();
$component->addLink( new Link( "#", "CeusMedia", NULL, "folder-open" ) );
$component->addLink( new Link( "#", "Bootstrap", NULL, "folder-open" ) );
$component->add( "Demo", NULL, NULL, "file" );

print '<h3>Breadcrumbs</h3>'.$component;
