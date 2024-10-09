<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu;
use CeusMedia\Bootstrap\Dropdown\Trigger as DropdownTrigger;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

$dropdown0	= new DropdownMenu();
$dropdown0->addLink( new Link( '#action-1-1', 'Link 1', '' , 'file') );
$dropdown0->add( '#action-1-2', 'Link 2', '', 'file' );

//$component	= new \CeusMedia\Bootstrap\Dropdown\Button( 'Dropdown-Button', $dropdown0, 'btn-info', 'star' );
$trigger	= new DropdownTrigger( 'Dropdown-Button', 'btn-info', 'star' );

$component	= HtmlTag::create( 'div', $trigger.$dropdown0, ['class' => 'btn-group'] );

print '<h3>DropdownButton</h3>'.$component;

/*print new CeusMedia\Bootstrap\Code( '
$dropdown	= new \CeusMedia\Bootstrap\Dropdown();
$dropdown->add( new \CeusMedia\Bootstrap\Link( "#", "Link 1" ) );
$component	= new \CeusMedia\Bootstrap\Dropdown\Button( "Dropdown-Button", $dropdown, "btn-info", "star" );
' );*/
