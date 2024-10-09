<?php

namespace CeusMedia\Bootstrap\Base\DataObject;

class NavPillItemLink
{
	public string $type		= 'link';
	public string $link;
	public string $class;

	public function __construct( string $link, string|null $class = NULL )
	{
		$this->link		= $link;
		$this->class	= $class ?? 'nav-item';
	}

	public static function create( string $link, string|null $class = NULL ): self
	{
		return new self( $link, $class );
	}
}