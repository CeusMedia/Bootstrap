<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base\Dialect;

use CeusMedia\Bootstrap\Base\BsVersion;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\Dialect\AbstractDialect
 * @covers			\CeusMedia\Bootstrap\Base\Dialect\AbstractDialect
 */
class DialectTest extends TestCase
{
	/**
	 *	@return		array<string,array{BsVersion,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> [BsVersion::V2, 'dropdown-menu pull-right close my-class', 'data-toggle'],
			'Bootstrap 3'	=> [BsVersion::V3, 'dropdown-menu dropdown-menu-right close my-class', 'data-toggle'],
			'Bootstrap 4'	=> [BsVersion::V4, 'dropdown-menu dropdown-menu-right close my-class', 'data-toggle'],
			'Bootstrap 5'	=> [BsVersion::V5, 'dropdown-menu dropdown-menu-end btn-close my-class', 'data-bs-toggle'],
		];
	}

	/**
	 *	@covers		::classes
	 *	@covers		::data
	 *	@covers		::dataKey
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testTranslation( BsVersion $version, string $expectedClasses, string $expectedData ): void
	{
		$dialect	= $version->dialect();
		self::assertSame( $expectedClasses, $dialect->classes( 'dropdown-menu  dropdown-menu-end', '', 'btn-close my-class', 'my-class' ) );
		self::assertSame( $expectedData, $dialect->data( 'toggle' ) );
	}
}
