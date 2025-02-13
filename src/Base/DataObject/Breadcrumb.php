<?php

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Bootstrap\Link;

class Breadcrumb
{
	public Link|string $label;
	public string $url;
	public string $class;
	public string $icon;
	public bool $active	= TRUE;
}