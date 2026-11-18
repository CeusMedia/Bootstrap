<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;

class Breadcrumb
{
	public Link|string $label;
	public string $url;
	public string $class;
	public Icon|string|null $icon	= NULL;
	public bool $active	= TRUE;
}
