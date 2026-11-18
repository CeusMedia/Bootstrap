<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Base\AlertClassResolver;
use CeusMedia\Bootstrap\Base\BsVersion;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\AlertClassResolver
 * @covers			\CeusMedia\Bootstrap\Base\AlertClassResolver
 * @covers			\CeusMedia\Bootstrap\Base\StateClassParser
 */
class AlertClassResolverTest extends TestCase
{
	/**
	 *	@return		array<string,array{list<string>,bool,string,string,string,string}>
	 */
	public static function classProvider(): array
	{
		return [
			'no state'				=> [[],								FALSE,	'alert',					'alert alert-warning',							'alert alert-warning',									'alert alert-warning'],
			'error (2)'				=> [['alert-error'],				FALSE,	'alert alert-error',		'alert alert-danger',							'alert alert-danger',									'alert alert-danger'],
			'danger (3+)'			=> [['alert alert-danger'],			FALSE,	'alert alert-error',		'alert alert-danger',							'alert alert-danger',									'alert alert-danger'],
			'warning'				=> [['alert-warning'],				FALSE,	'alert',					'alert alert-warning',							'alert alert-warning',									'alert alert-warning'],
			'success'				=> [['alert-success'],				FALSE,	'alert alert-success',		'alert alert-success',							'alert alert-success',									'alert alert-success'],
			'primary (4+)'			=> [['alert-primary'],				FALSE,	'alert alert-info',			'alert alert-info',								'alert alert-primary',									'alert alert-primary'],
			'dark (4+)'				=> [['alert-dark'],					FALSE,	'alert alert-info',			'alert alert-info',								'alert alert-dark',										'alert alert-dark'],
			'dismissible'			=> [['alert-info'],					TRUE,	'alert alert-info',			'alert alert-info alert-dismissible',			'alert alert-info alert-dismissible fade show',			'alert alert-info alert-dismissible fade show'],
			'others kept'			=> [['alert-info my-alert x x'],	FALSE,	'alert alert-info my-alert x','alert alert-info my-alert x',				'alert alert-info my-alert x',							'alert alert-info my-alert x'],
		];
	}

	/**
	 *	@covers		::resolve
	 *	@covers		::realizeState
	 *	@dataProvider	classProvider
	 *	@param		list<string>	$classes
	 *	@return		void
	 */
	public function testResolve( array $classes, bool $dismissible, string $expectedV2, string $expectedV3, string $expectedV4, string $expectedV5 ): void
	{
		self::assertSame( $expectedV2, join( ' ', AlertClassResolver::resolve( BsVersion::V2, $classes, $dismissible ) ) );
		self::assertSame( $expectedV3, join( ' ', AlertClassResolver::resolve( BsVersion::V3, $classes, $dismissible ) ) );
		self::assertSame( $expectedV4, join( ' ', AlertClassResolver::resolve( BsVersion::V4, $classes, $dismissible ) ) );
		self::assertSame( $expectedV5, join( ' ', AlertClassResolver::resolve( BsVersion::V5, $classes, $dismissible ) ) );
	}
}
