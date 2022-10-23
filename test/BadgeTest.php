<?php
namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Badge;
use PHPUnit\Framework\TestCase;

class BadgeTest extends TestCase
{
	public function testConstruct()
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
	 */
	public function testRender()
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
