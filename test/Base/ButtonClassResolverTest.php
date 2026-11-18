<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\ButtonClassResolver;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\ButtonClassResolver
 * @covers			\CeusMedia\Bootstrap\Base\ButtonClassResolver
 */
class ButtonClassResolverTest extends TestCase
{
	/**
	 *	@return		array<string,array{list<string>,string,string,string,string}>
	 */
	public static function classProvider(): array
	{
		return [
			'no state'				=> [[],								'btn',							'btn btn-default',				'btn btn-secondary',				'btn btn-secondary'],
			'default (2/3)'			=> [['btn btn-default'],			'btn',							'btn btn-default',				'btn btn-secondary',				'btn btn-secondary'],
			'secondary (4+)'		=> [['btn-secondary'],				'btn',							'btn btn-default',				'btn btn-secondary',				'btn btn-secondary'],
			'primary'				=> [['btn-primary'],				'btn btn-primary',				'btn btn-primary',				'btn btn-primary',					'btn btn-primary'],
			'inverse (2)'			=> [['btn-inverse'],				'btn btn-inverse',				'btn btn-default',				'btn btn-dark',						'btn btn-dark'],
			'dark (4+)'				=> [['btn-dark'],					'btn btn-inverse',				'btn btn-default',				'btn btn-dark',						'btn btn-dark'],
			'light (4+)'			=> [['btn-light'],					'btn',							'btn btn-default',				'btn btn-light',					'btn btn-light'],
			'link'					=> [['btn-link'],					'btn btn-link',					'btn btn-link',					'btn btn-link',						'btn btn-link'],
			'outline (4+)'			=> [['btn-outline-danger'],			'btn btn-danger',				'btn btn-danger',				'btn btn-outline-danger',			'btn btn-outline-danger'],
			'outline secondary'		=> [['btn-outline-secondary'],		'btn',							'btn btn-default',				'btn btn-outline-secondary',		'btn btn-outline-secondary'],
			'mini (2)'				=> [['btn-mini'],					'btn btn-mini',					'btn btn-default btn-xs',		'btn btn-secondary btn-sm',			'btn btn-secondary btn-sm'],
			'small (2 and 3+)'		=> [['btn-success btn-small btn-sm'],'btn btn-success btn-small',	'btn btn-success btn-sm',		'btn btn-success btn-sm',			'btn btn-success btn-sm'],
			'large (2 and 3+)'		=> [['btn-large btn-lg'],			'btn btn-large',				'btn btn-default btn-lg',		'btn btn-secondary btn-lg',			'btn btn-secondary btn-lg'],
			'block'					=> [['btn-info', 'btn-block'],		'btn btn-info btn-block',		'btn btn-info btn-block',		'btn btn-info btn-block',			'btn btn-info w-100'],
			'others kept'			=> [['dropdown-toggle btn-info x x'],'btn btn-info dropdown-toggle x','btn btn-info dropdown-toggle x','btn btn-info dropdown-toggle x','btn btn-info dropdown-toggle x'],
		];
	}

	/**
	 *	@covers		::resolve
	 *	@dataProvider	classProvider
	 *	@param		list<string>	$classes
	 *	@return		void
	 */
	public function testResolve( array $classes, string $expectedV2, string $expectedV3, string $expectedV4, string $expectedV5 ): void
	{
		self::assertSame( $expectedV2, join( ' ', ButtonClassResolver::resolve( BsVersion::V2, $classes ) ) );
		self::assertSame( $expectedV3, join( ' ', ButtonClassResolver::resolve( BsVersion::V3, $classes ) ) );
		self::assertSame( $expectedV4, join( ' ', ButtonClassResolver::resolve( BsVersion::V4, $classes ) ) );
		self::assertSame( $expectedV5, join( ' ', ButtonClassResolver::resolve( BsVersion::V5, $classes ) ) );
	}
}
