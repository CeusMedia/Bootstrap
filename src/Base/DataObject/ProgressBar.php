<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Bootstrap\Base\DataObject;

class ProgressBar
{
	public int|float $width;
	public string|NULL $class;
	public string $label;

	public function __construct( int|float $width, ?string $class = NULL, string|NULL $label = NULL )
	{
		$this->width	= $width;
		$this->class	= $class;
		$this->label	= $label ?? '';
	}

	public static function create( int|float $width, ?string $class = NULL, string|NULL $label = NULL ): self
	{
		return new self( $width, $class, $label );
	}
}