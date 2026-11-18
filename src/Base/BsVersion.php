<?php
declare(strict_types=1);

/**
 *	Supported major versions of Bootstrap.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

use CeusMedia\Bootstrap\Base\Dialect\Dialect;
use CeusMedia\Bootstrap\Base\Dialect\V2;
use CeusMedia\Bootstrap\Base\Dialect\V3;
use CeusMedia\Bootstrap\Base\Dialect\V4;
use CeusMedia\Bootstrap\Base\Dialect\V5;
use DomainException;

/**
 *	Supported major versions of Bootstrap.
 *
 *	Components decide on structural differences by matching all cases explicitly,
 *	without default arm. So adding a new version (like V6) makes PHPStan report
 *	every place needing a decision for the new version.
 *
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
enum BsVersion: int
{
	case V2	= 2;
	case V3	= 3;
	case V4	= 4;
	case V5	= 5;

	/**
	 *	Resolves version enum case from version string, like "2.3.2" or "5".
	 *	@param		string		$version		Version string, only major version is relevant
	 *	@return		self
	 *	@throws		DomainException			if major version is not supported
	 */
	public static function fromString( string $version ): self
	{
		$major	= (int) explode( '.', trim( $version ), 2 )[0];
		return self::tryFrom( $major )
			?? throw new DomainException( 'Unsupported Bootstrap version: '.$version );
	}

	/**
	 *	Returns dialect, translating neutral (Bootstrap 5) class names and data attributes for this version.
	 *	@return		Dialect
	 */
	public function dialect(): Dialect
	{
		return match( $this ){
			self::V2	=> new V2(),
			self::V3	=> new V3(),
			self::V4	=> new V4(),
			self::V5	=> new V5(),
		};
	}
}
