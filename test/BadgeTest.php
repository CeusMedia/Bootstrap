<?php
use CeusMedia\Bootstrap\Badge;

class BadgeTest extends PHPUnit\Framework\TestCase
{
	public function testConstruct()
	{
		$text	= 'Badge Text';
		$badge	= new UnprotectedBadge( $text );

		$this->assertEquals( $text, $badge->getContent() );
		$this->assertEquals( [], $badge->getClasses() );

		$class	= Badge::CLASS_WARNING;
		$badge	= new UnprotectedBadge( $text, $class );
		$this->assertEquals( [$class], $badge->getClasses() );
	}

	/**
	 *	@covers		::render
	 */
	public function testRender()
	{
		$text	= 'Badge Text';
		$badge	= new UnprotectedBadge( $text );

		$expected	= '<span class="badge">Badge Text</span>';
		$this->assertEquals( $expected, $badge->render() );

		$class	= Badge::CLASS_SUCCESS;
		$badge	= new UnprotectedBadge( $text, $class );

		$expected	= '<span class="badge badge-success">Badge Text</span>';
		$this->assertEquals( $expected, $badge->render() );
	}
}

class UnprotectedBadge extends Badge
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
