<?php /** @noinspection ALL */
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Label;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Label
 * @covers			\CeusMedia\Bootstrap\Label
 * @covers			\CeusMedia\Bootstrap\Base\StateClassParser
 * @covers			\CeusMedia\Bootstrap\Badge
 */
class LabelTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$text	= 'Label Text';
		$label	= new UnprotectedLabel( $text );

		self::assertEquals( $text, $label->getContent() );
		self::assertEquals( [], $label->getClasses() );

		$class	= Label::CLASS_WARNING;
		$label	= new UnprotectedLabel( $text, $class );
		self::assertEquals( [$class], $label->getClasses() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$text	= 'Label Text';
		$label	= new UnprotectedLabel( $text );

		$expected	= '<span class="label">Label Text</span>';
		self::assertEquals( $expected, $label->render() );

		$class	= Label::CLASS_SUCCESS;
		$label	= new UnprotectedLabel( $text, $class );

		$expected	= '<span class="label label-success">Label Text</span>';
		self::assertEquals( $expected, $label->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'label', 'label label-important', 'label label-inverse'],
			'Bootstrap 3'	=> ['3.4.1', 'label label-default', 'label label-danger', 'label label-default'],
			'Bootstrap 4'	=> ['4.6.2', 'badge badge-secondary', 'badge badge-danger', 'badge badge-dark'],
			'Bootstrap 5'	=> ['5.3.3', 'badge bg-secondary', 'badge bg-danger', 'badge bg-dark'],
		];
	}

	/**
	 *	@covers		::render
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderForVersion( string $version, string $default, string $important, string $inverse ): void
	{
		self::assertSame( '<span class="'.$default.'">L</span>', ( new Label( 'L' ) )->setBsVersion( $version )->render() );
		self::assertSame( '<span class="'.$important.'">L</span>', ( new Label( 'L', Label::CLASS_IMPORTANT ) )->setBsVersion( $version )->render() );
		self::assertSame( '<span class="'.$inverse.'">L</span>', ( new Label( 'L', Label::CLASS_INVERSE ) )->setBsVersion( $version )->render() );
	}

	/**
	 *	Class names of Bootstrap 3 are understood as well.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderDefaultOfBootstrap3(): void
	{
		self::assertSame( '<span class="badge bg-secondary">L</span>', ( new Label( 'L', 'label-default' ) )->setBsVersion( '5' )->render() );
	}
}

class UnprotectedLabel extends Label
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
