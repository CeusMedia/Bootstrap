<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Button;

use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Button\Submit as SubmitButton;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Button\Submit
 */
class SubmitTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$name	= 'save';
		$label	= 'Button Label';
		$button	= new UnprotectedSubmitButton( $name, $label );

		self::assertEquals( $name, $button->getName() );
		self::assertEquals( $label, $button->getContent() );
		self::assertEquals( [], $button->getClasses() );

		$class	= Button::STATE_PRIMARY;
		$button	= new UnprotectedSubmitButton( $name, $label, $class );
		self::assertEquals( [$class], $button->getClasses() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$name	= 'save';
		$label	= 'Button Label';
		$button	= new SubmitButton( $name, $label );

		$expected	= '<button name="save" type="submit" class="btn">Button Label</button>';
		self::assertEquals( $expected, $button->render() );

		$button		= new SubmitButton( $name, $label, Button::STATE_PRIMARY );
		$expected	= '<button name="save" type="submit" class="btn btn-primary">Button Label</button>';
		self::assertEquals( $expected, $button->render() );
	}

	/**
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\SizeAware::getSize
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\SizeAware::setSize
	 *	@return		void
	 */
	public function testSetSize(): void
	{
		$button	= new UnprotectedSubmitButton( 'sizeTest', 'SizeAware' );

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
		$button	= new UnprotectedSubmitButton( 'blockTest', 'Block Button' );
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
}

class UnprotectedSubmitButton extends SubmitButton
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
