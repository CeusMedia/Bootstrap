<?php

namespace CeusMedia\Bootstrap\Base\DataObject;

use CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu;
use CeusMedia\Bootstrap\Icon;

class NavPillItemDropdown
{
	public string $type						= 'dropdown';

	public string $label;

	public DropdownMenu|string $content;

	public string $class;

	public Icon|string|null $icon			= NULL;

	public Icon|string|null $iconActive		= NULL;

	public function __construct(
		string $label,
		DropdownMenu|string $dropdown,
		string|null $class = NULL,
		Icon|string|null $icon = NULL,
		Icon|string|null $iconActive = NULL
	)
	{
		$this->label		= $label;
		$this->content		= $dropdown;
		$this->class		= 'nav-link'.( $class ? ' '.$class : '' );
		$this->icon			= $icon;
		$this->iconActive	= $iconActive;
	}

	public static function create(
		string $label,
		DropdownMenu|string $dropdown,
		string|null $class = NULL,
		Icon|string|null $icon = NULL,
		Icon|string|null $iconActive = NULL
	): self
	{
		return new self( $label, $dropdown, $class, $icon, $iconActive );
	}
}