<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Badge
 * @covers			\CeusMedia\Bootstrap\Badge
 * @covers			\CeusMedia\Bootstrap\Base\StateClassParser
 */
class BadgeTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$text	= 'Badge Text';
		$badge	= new UnprotectedBadge( $text );

		self::assertEquals( $text, $badge->getContent() );
		self::assertEquals( [], $badge->getClasses() );

		$class	= Badge::CLASS_WARNING;
		$badge	= new UnprotectedBadge( $text, $class );
		self::assertEquals( [$class], $badge->getClasses() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$text	= 'Badge Text';
		$badge	= new UnprotectedBadge( $text );

		$expected	= '<span class="badge">Badge Text</span>';
		self::assertEquals( $expected, $badge->render() );

		$class	= Badge::CLASS_SUCCESS;
		$badge	= new UnprotectedBadge( $text, $class );

		$expected	= '<span class="badge badge-success">Badge Text</span>';
		self::assertEquals( $expected, $badge->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'badge', 'badge badge-important', 'badge badge-warning'],
			'Bootstrap 3'	=> ['3.4.1', 'badge', 'badge" style="background-color: #d9534f', 'badge" style="background-color: #f0ad4e'],
			'Bootstrap 4'	=> ['4.6.2', 'badge badge-secondary badge-pill', 'badge badge-danger badge-pill', 'badge badge-warning badge-pill'],
			'Bootstrap 5'	=> ['5.3.3', 'badge bg-secondary rounded-pill', 'badge bg-danger rounded-pill', 'badge bg-warning text-dark rounded-pill'],
		];
	}

	/**
	 *	@covers		::render
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderForVersion( string $version, string $default, string $important, string $warning ): void
	{
		self::assertSame( '<span class="'.$default.'">1</span>', ( new Badge( '1' ) )->setBsVersion( $version )->render() );
		self::assertSame( '<span class="'.$important.'">1</span>', ( new Badge( '1', Badge::CLASS_IMPORTANT ) )->setBsVersion( $version )->render() );
		self::assertSame( '<span class="'.$warning.'">1</span>', ( new Badge( '1', Badge::CLASS_WARNING ) )->setBsVersion( $version )->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderInverse(): void
	{
		self::assertSame( '<span class="badge badge-inverse">1</span>', ( new Badge( '1', Badge::CLASS_INVERSE ) )->setBsVersion( '2' )->render() );
		self::assertSame( '<span class="badge badge-dark badge-pill">1</span>', ( new Badge( '1', Badge::CLASS_INVERSE ) )->setBsVersion( '4' )->render() );
	}
}

class UnprotectedBadge extends Badge
{
	public function getClasses(): array
	{
		return $this->classes;
	}

	public function getContent(): Renderable|Stringable|array|string|NULL
	{
		return $this->content;
	}
}
