<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Button;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use RangeException;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Button
 * @covers			\CeusMedia\Bootstrap\Button
 * @covers			\CeusMedia\Bootstrap\Base\ButtonClassResolver
 */
class ButtonTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$label	= 'Button Label';
		$button	= new UnprotectedButton( $label );

		self::assertEquals( $label, $button->getContent() );
		self::assertEquals( [], $button->getClasses() );

		$class	= Button::STATE_PRIMARY;
		$button	= new UnprotectedButton( $label, $class );
		self::assertEquals( [$class], $button->getClasses() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$label	= 'Button Label';
		$button	= new Button( $label );

		$expected	= '<button type="button" class="btn">Button Label</button>';
		self::assertEquals( $expected, $button->render() );

		$button		= new Button( $label, Button::STATE_PRIMARY );
		$expected	= '<button type="button" class="btn btn-primary">Button Label</button>';
		self::assertEquals( $expected, $button->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'btn', 'btn btn-inverse btn-mini btn-block'],
			'Bootstrap 3'	=> ['3.4.1', 'btn btn-default', 'btn btn-default btn-xs btn-block'],
			'Bootstrap 4'	=> ['4.6.2', 'btn btn-secondary', 'btn btn-dark btn-sm btn-block'],
			'Bootstrap 5'	=> ['5.3.3', 'btn btn-secondary', 'btn btn-dark btn-sm w-100'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setBlock
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderForVersion( string $version, string $expectedDefault, string $expectedStyled ): void
	{
		$button		= ( new Button( 'Label' ) )->setBsVersion( $version );
		self::assertEquals( '<button type="button" class="'.$expectedDefault.'">Label</button>', $button->render() );

		$button		= ( new Button( 'Label', Button::STATE_INVERSE ) )->setBsVersion( $version );
		$button->setSize( Button::SIZE_MINI )->setBlock();
		self::assertEquals( '<button type="button" class="'.$expectedStyled.'">Label</button>', $button->render() );
	}

	/**
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\SizeAware::getSize
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\SizeAware::setSize
	 *	@return		void
	 */
	public function testSetSize(): void
	{
		$button	= new UnprotectedButton( 'SizeAware' );

		self::assertEquals( [], $button->getClasses() );
		self::assertEquals( 'SIZE_DEFAULT', $button->getSize() );

		$button->setSize( Button::SIZE_MINI );
		self::assertEquals( 'SIZE_MINI', $button->getSize() );
		self::assertEquals( Button::SIZE_MINI, join( ' ', $button->getClasses() ) );

		$button->setSize( Button::SIZE_SMALL );
		self::assertEquals( 'SIZE_SMALL', $button->getSize() );
		self::assertEquals( Button::SIZE_SMALL, join( ' ', $button->getClasses() ) );

		$button->setSize( Button::SIZE_LARGE );
		self::assertEquals( 'SIZE_LARGE', $button->getSize() );
		self::assertEquals( Button::SIZE_LARGE, join( ' ', $button->getClasses() ) );
	}

	/**
	 *	@covers		::setBlock
	 *	@return		void
	 */
	public function testSetBlock(): void
	{
		$button	= new UnprotectedButton( 'Block Button' );
		self::assertEquals( [], $button->getClasses() );

		$button->setBlock();
		self::assertEquals( ['btn-block'], $button->getClasses() );

		$button->setBlock( FALSE );
		self::assertEquals( [], $button->getClasses() );

		$button->setSize( Button::SIZE_LARGE );
		/** @noinspection PhpRedundantOptionalArgumentInspection */
		$button->setBlock( TRUE );
		self::assertEquals( ['btn-large', 'btn-lg', 'btn-block'], $button->getClasses() );

		$button->setBlock( FALSE );
		self::assertEquals( ['btn-large', 'btn-lg'], $button->getClasses() );
	}

	/**
	 *	@covers		::setType
	 *	@return		void
	 */
	public function testSetType(): void
	{
		$button	= new Button( 'Typed Button', NULL, 'icon-ok' );
		self::assertSame( '<button type="button" class="btn"><i class="icon-icon-ok"></i> Typed Button</button>', $button->render() );
		self::assertStringStartsWith( '<button type="submit"', $button->setType( Button::TYPE_SUBMIT )->render() );
		self::assertStringStartsWith( '<button type="reset"', $button->setType( Button::TYPE_RESET )->render() );

		$this->expectException( RangeException::class );
		$button->setType( 'link' );
	}
}

class UnprotectedButton extends Button
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
