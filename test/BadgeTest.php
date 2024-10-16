<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Badge
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
