<?php

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Bootstrap\Dropdown\Menu;
use CeusMedia\Common\Renderable;

class MenuItem
{
	public string $type						= 'link';
	public string|Renderable|NULL $content	= NULL;
	public ?string $class					= '';
	public ?string $icon					= '';
	public bool $disabled					= FALSE;

	/** @var ?Menu $menu */
	public ?Menu $submenu					= NULL;
}