<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Nav\NavList;

$navList = new NavList();

$navList->addHeader( 'Topic 1', 'cube' );
$navList->add( '#link1', 'Link 1', 'star', 'active' );
$navList->add( '#link2', 'Link 2', 'road' );
$navList->addHeader( 'Topic 2', 'folder' );
$navList->add( '#link3', 'Link 3', 'book' );
$navList->addDivider();
$navList->add( '#link4', 'Link 4', 'link' );

print '<h3>Nav: List</h3><div class="row-fluid"><div class="span3"><div class="well">'.$navList->render().'</div></div></div>';
