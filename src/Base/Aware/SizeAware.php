<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Common\Alg\Obj\Constant as ClassConstantReflector;

use RangeException;

use function array_intersect;
use function in_array;
use function preg_split;
use function strlen;

trait SizeAware
{
	/**
	 *	Tries to identify a set size.
	 *	This is not working correctly, if the map of sizes is not surjective or contains empty values.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getSize(): ?string
	{
		$foundSize	= NULL;
		foreach( static::SIZES as $size ){
			if( strlen( $size ) === 0 ){
				$foundSize	= $size;
				continue;
			}
			/** @var array<string> $sizeClasses */
			$sizeClasses = preg_split( '/\s+/', $size );
			if( count( array_intersect( $sizeClasses, $this->classes ) ) > 0 ){
				$foundSize	= $size;
			}
		}
		if( $foundSize !== NULL ){
			$reflector	= new ClassConstantReflector( static::class );
			$constant	= $reflector->getKeyByValue( $foundSize, 'SIZE_' );
			return 'SIZE_'.$constant;
		}
		return NULL;
	}

	/**
	 *	Sets size by adding class(es) assigned to size constant.
	 *	Removes beforehand set size.
	 *	@access		public
	 *	@param		string		$size		...
	 *	@return		self
	 */
	public function setSize( string $size ): self
	{
		if( !in_array( $size, static::SIZES, TRUE ) )
			throw new RangeException( 'Invalid size' );
		foreach( static::SIZES as $otherSize ){
			if( strlen( $otherSize ) > 0 ){
				/** @var array<string> $sizeClasses */
				$sizeClasses = preg_split( '/\s+/', $otherSize );
				foreach( $sizeClasses as $sizeClass )
					$this->removeClass( $sizeClass );
			}
		}
		$this->addClass( $size );
		return $this;
	}
}
