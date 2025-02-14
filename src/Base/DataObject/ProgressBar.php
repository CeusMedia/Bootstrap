<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

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

	/**
	 *	@param		int|float		$width
	 *	@param		string|null		$class
	 *	@param		string|NULL		$label
	 *	@return		static
	 */
	public static function create( int|float $width, ?string $class = NULL, string $label = NULL ): static
	{
		$className	= static::class;
		return new $className( $width, $class, $label );
	}
}