<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Dialect;

/**
 *	Table based dialect: neutral class names are translated by map CLASSES.
 *	A mapped empty string removes the class for this version.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
abstract class AbstractDialect implements Dialect
{
	/** @var array<string,string> Map of neutral class name to class name(s) of this version */
	protected const array CLASSES	= [];

	/** @var string Prefix of data attribute keys, like "bs-" in Bootstrap 5 */
	protected const string DATA_PREFIX	= '';

	public function classes( string ...$classes ): string
	{
		$list	= [];
		foreach( $classes as $class ){
			$tokens	= preg_split( '/\s+/', trim( $class ), -1, PREG_SPLIT_NO_EMPTY );
			foreach( FALSE !== $tokens ? $tokens : [] as $token ){
				$translated	= static::CLASSES[$token] ?? $token;
				if( '' !== $translated && !in_array( $translated, $list, TRUE ) )
					$list[]	= $translated;
			}
		}
		return join( ' ', $list );
	}

	public function dataKey( string $name ): string
	{
		return static::DATA_PREFIX.$name;
	}

	public function data( string $name ): string
	{
		return 'data-'.$this->dataKey( $name );
	}
}
