<?php
declare(strict_types=1);

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

	public static function create(
		string $label,
		DropdownMenu|string $dropdown,
		string $class = NULL,
		Icon|string $icon = NULL,
		Icon|string $iconActive = NULL
	): static
	{
		$className	= static::class;
		return new $className( $label, $dropdown, $class, $icon, $iconActive );
	}

	public function __construct(
		string $label,
		DropdownMenu|string $dropdown,
		string $class = NULL,
		Icon|string $icon = NULL,
		Icon|string $iconActive = NULL
	)
	{
		$this->label		= $label;
		$this->content		= $dropdown;
		$this->class		= 'nav-link'.( NULL !== $class ? ' '.$class : '' );
		$this->icon			= $icon;
		$this->iconActive	= $iconActive;
	}
}