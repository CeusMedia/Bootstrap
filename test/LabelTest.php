<?php
use CeusMedia\Bootstrap\Label;

class LabelTest extends PHPUnit\Framework\TestCase
{
	public function testConstruct()
	{
		$text	= 'Label Text';
		$label	= new UnprotectedLabel( $text );

		$this->assertEquals( $text, $label->getContent() );
		$this->assertEquals( [], $label->getClasses() );

		$class	= Label::CLASS_WARNING;
		$label	= new UnprotectedLabel( $text, $class );
		$this->assertEquals( [$class], $label->getClasses() );
	}

	/**
	 *	@covers		::render
	 */
	public function testRender()
	{
		$text	= 'Label Text';
		$label	= new UnprotectedLabel( $text );

		$expected	= '<span class="label">Label Text</span>';
		$this->assertEquals( $expected, $label->render() );

		$class	= Label::CLASS_SUCCESS;
		$label	= new UnprotectedLabel( $text, $class );

		$expected	= '<span class="label label-success">Label Text</span>';
		$this->assertEquals( $expected, $label->render() );
	}
}

class UnprotectedLabel extends Label
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
