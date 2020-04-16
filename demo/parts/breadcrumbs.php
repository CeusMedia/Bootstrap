<?php
$component	= new CeusMedia\Bootstrap\Breadcrumbs();
$component->addLink( new CeusMedia\Bootstrap\Link( "#", "CeusMedia", NULL, "folder-open" ) );
$component->addLink( new CeusMedia\Bootstrap\Link( "#", "Bootstrap", NULL, "folder-open" ) );
$component->add( "Demo", NULL, NULL, "file" );
print '<h3>Breadcrumbs</h3>'.$component;
?>
