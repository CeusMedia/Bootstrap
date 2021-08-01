<?php
use CeusMedia\Bootstrap\Button;

class ButtonTest extends PHPUnit\Framework\TestCase
{
	public function testConstruct()
	{
		$label	= 'Button Label';
		$button	= new UnprotectedButton( $label );

		$this->assertEquals( $label, $button->getContent() );
		$this->assertEquals( [], $button->getClasses() );

		$class	= Button::STATE_PRIMARY;
		$button	= new UnprotectedButton( $label, $class );
		$this->assertEquals( [$class], $button->getClasses() );

		$class	= Button::STATE_PRIMARY;
		$button	= new UnprotectedButton( $label, $class );
		$this->assertEquals( [$class], $button->getClasses() );
	}

	/**
	 *	@covers		::render
	 */
	public function testRender()
	{
		$label	= 'Button Label';
		$button	= new Button( $label );

		$expected	= '<button type="button" class="btn">Button Label</button>';
		$this->assertEquals( $expected, $button->render() );

		$class	= Button::STATE_PRIMARY;
		$button	= new Button( $label, $class );

		$expected	= '<button type="button" class="btn btn-primary">Button Label</button>';
		$this->assertEquals( $expected, $button->render() );

		$class	= Button::STATE_PRIMARY;
		$button	= new Button( $label, $class );

		$expected	= '<button type="button" class="btn btn-primary">Button Label</button>';
		$this->assertEquals( $expected, $button->render() );
	}

	/**
	 *	@covers		CeusMedia\Bootstrap\Aware\SizeAware::getSize
	 *	@covers		CeusMedia\Bootstrap\Aware\SizeAware::setSize
	 */
	public function testSetSize()
	{
		$button	= new UnprotectedButton( 'SizeAware' );

		$this->assertEquals( [], $button->getClasses() );
		$this->assertEquals( 'SIZE_DEFAULT', $button->getSize() );

		$button->setSize( Button::SIZE_MINI );
		$this->assertEquals( 'SIZE_MINI', $button->getSize() );
		$this->assertEquals( Button::SIZE_MINI, join( ' ', $button->getClasses() ) );

		$button->setSize( Button::SIZE_SMALL );
		$this->assertEquals( 'SIZE_SMALL', $button->getSize() );
		$this->assertEquals( Button::SIZE_SMALL, join( ' ', $button->getClasses() ) );

		$button->setSize( Button::SIZE_LARGE );
		$this->assertEquals( 'SIZE_LARGE', $button->getSize() );
		$this->assertEquals( Button::SIZE_LARGE, join( ' ', $button->getClasses() ) );
	}

	public function testSetBlock()
	{
		$button	= new UnprotectedButton( 'Block Button' );
		$this->assertEquals( [], $button->getClasses() );

		$button->setBlock();
		$this->assertEquals( ['btn-block'], $button->getClasses() );

		$button->setBlock( FALSE );
		$this->assertEquals( [], $button->getClasses() );

		$button->setSize( Button::SIZE_LARGE );
		$button->setBlock( TRUE );
		$this->assertEquals( ['btn-large', 'btn-lg', 'btn-block'], $button->getClasses() );
		$button->setBlock( FALSE );
		$this->assertEquals( ['btn-large', 'btn-lg'], $button->getClasses() );
	}

	public function testSetType()
	{
		$button	= new UnprotectedButton( 'Typed Button' );

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
