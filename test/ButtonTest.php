<?php
namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Button;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
	public function testConstruct()
	{
		$label	= 'Button Label';
		$button	= new UnprotectedButton( $label );

		self::assertEquals( $label, $button->getContent() );
		self::assertEquals( [], $button->getClasses() );

		$class	= Button::STATE_PRIMARY;
		$button	= new UnprotectedButton( $label, $class );
		self::assertEquals( [$class], $button->getClasses() );

		$class	= Button::STATE_PRIMARY;
		$button	= new UnprotectedButton( $label, $class );
		self::assertEquals( [$class], $button->getClasses() );
	}

	/**
	 *	@covers		::render
	 */
	public function testRender()
	{
		$label	= 'Button Label';
		$button	= new Button( $label );

		$expected	= '<button type="button" class="btn">Button Label</button>';
		self::assertEquals( $expected, $button->render() );

		$class	= Button::STATE_PRIMARY;
		$button	= new Button( $label, $class );

		$expected	= '<button type="button" class="btn btn-primary">Button Label</button>';
		self::assertEquals( $expected, $button->render() );

		$class	= Button::STATE_PRIMARY;
		$button	= new Button( $label, $class );

		$expected	= '<button type="button" class="btn btn-primary">Button Label</button>';
		self::assertEquals( $expected, $button->render() );
	}

	/**
	 *	@covers		CeusMedia\Bootstrap\Aware\SizeAware::getSize
	 *	@covers		CeusMedia\Bootstrap\Aware\SizeAware::setSize
	 */
	public function testSetSize()
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

	public function testSetBlock()
	{
		$button	= new UnprotectedButton( 'Block Button' );
		self::assertEquals( [], $button->getClasses() );

		$button->setBlock();
		self::assertEquals( ['btn-block'], $button->getClasses() );

		$button->setBlock( FALSE );
		self::assertEquals( [], $button->getClasses() );

		$button->setSize( Button::SIZE_LARGE );
		$button->setBlock( TRUE );
		self::assertEquals( ['btn-large', 'btn-lg', 'btn-block'], $button->getClasses() );
		$button->setBlock( FALSE );
		self::assertEquals( ['btn-large', 'btn-lg'], $button->getClasses() );
	}

	public function testSetType()
	{
		self::markTestSkipped();
//		$button	= new UnprotectedButton( 'Typed Button' );

	}
}

class UnprotectedButton extends Button
{
	public function getClasses(): array
	{
		return $this->classes;
	}

	public function getContent(): string
	{
		return $this->content;
	}
}
