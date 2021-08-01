<?php

use CeusMedia\Bootstrap\Link;

$link	= new Link( '#', 'Normal Link' );

$list	= [];
$list[]	= $link->render();

$link->setContent( 'Link with click event' );
$link->setEvent( 'click', 'alert(this.nodeName)' );
$list[]	= $link->render();

$link->setDisabled();
$link->setContent( 'Disabled Link' );
$list[]	= $link->render();

print '<h3>Links</h3><ul><li>'.join( '</li><li>', $list ).'</li></ul>';
