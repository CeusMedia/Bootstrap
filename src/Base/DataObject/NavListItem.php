<?php
declare(strict_types = 1);

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Nav\NavList;
use CeusMedia\Common\ADT\URL;

class NavListItem
{
	public string $type				= 'link';
	public string $label;
	public URL|string|NULL $url		= NULL;
	public Icon|NULL $icon			= NULL;
	public string|NULL $class		= NULL;

	public NavList|NULL $list		= NULL;
}