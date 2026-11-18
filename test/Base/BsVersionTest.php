<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Dialect\V2;
use CeusMedia\Bootstrap\Base\Dialect\V5;
use CeusMedia\Bootstrap\Badge;
use DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\BsVersion
 * @covers			\CeusMedia\Bootstrap\Base\BsVersion
 */
class BsVersionTest extends TestCase
{
	/**
	 *	@covers		::fromString
	 *	@return		void
	 */
	public function testFromString(): void
	{
		self::assertSame( BsVersion::V2, BsVersion::fromString( '2.3.2' ) );
		self::assertSame( BsVersion::V3, BsVersion::fromString( '3' ) );
		self::assertSame( BsVersion::V4, BsVersion::fromString( ' 4.6.2 ' ) );
		self::assertSame( BsVersion::V5, BsVersion::fromString( '5.3.3' ) );
	}

	/**
	 *	@covers		::fromString
	 *	@return		void
	 */
	public function testFromStringThrowsOnUnsupportedVersion(): void
	{
		$this->expectException( DomainException::class );
		BsVersion::fromString( '6.0.0' );
	}

	/**
	 *	@covers		::dialect
	 *	@return		void
	 */
	public function testDialect(): void
	{
		self::assertInstanceOf( V2::class, BsVersion::V2->dialect() );
		self::assertInstanceOf( V5::class, BsVersion::V5->dialect() );
	}

	/**
	 *	@covers		\CeusMedia\Bootstrap\Base\Abstraction::setBsVersion
	 *	@covers		\CeusMedia\Bootstrap\Base\Abstraction::getBsVersion
	 *	@return		void
	 */
	public function testSetBsVersionOnComponent(): void
	{
		$badge	= new Badge( 'x' );
		self::assertSame( BsVersion::V4, $badge->setBsVersion( '4.6.2' )->getBsVersion() );
		self::assertSame( BsVersion::V5, $badge->setBsVersion( BsVersion::V5 )->getBsVersion() );

		$this->expectException( DomainException::class );
		$badge->setBsVersion( '1.4' );
	}
}
