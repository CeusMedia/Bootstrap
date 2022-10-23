<?php
namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Label;
use PHPUnit\Framework\TestCase;

class LabelTest extends TestCase
{
	public function testConstruct()
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
	 */
	public function testRender()
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
