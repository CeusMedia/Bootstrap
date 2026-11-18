<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Button;

use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Button\Group;
use CeusMedia\Bootstrap\Button\Toolbar;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Button\Group
 * @covers			\CeusMedia\Bootstrap\Button\Group
 * @covers			\CeusMedia\Bootstrap\Button\Toolbar
 */
class GroupTest extends TestCase
{
	/**
	 *	@covers		::render
	 *	@covers		::add
	 *	@return		void
	 */
	public function testRender(): void
	{
		$group	= new Group( [new Button( 'A' ), new Button( 'B' )] );
		$group->add( '<span>C</span>' )->add( [new Button( 'D' )] )->addClass( 'my-group' );
		$expected	= '<div class="btn-group my-group" role="group">'.
			'<button type="button" class="btn">A</button>'.
			'<button type="button" class="btn">B</button>'.
			'<span>C</span>'.
			'<button type="button" class="btn">D</button>'.
			'</div>';
		self::assertSame( $expected, $group->render() );
	}

	/**
	 *	@covers		::setStacked
	 *	@return		void
	 */
	public function testRenderStacked(): void
	{
		$group	= new Group( [], TRUE );
		self::assertSame( '<div class="btn-group btn-group-vertical" role="group"></div>', $group->render() );
		self::assertSame( '<div class="btn-group" role="group"></div>', $group->setStacked( FALSE )->render() );
	}

	/**
	 *	@covers		\CeusMedia\Bootstrap\Button\Toolbar::render
	 *	@covers		\CeusMedia\Bootstrap\Button\Toolbar::add
	 *	@return		void
	 */
	public function testToolbar(): void
	{
		$toolbar	= new Toolbar( [new Group( [new Button( 'A' )] )] );
		$toolbar->add( '' )->add( '<span>B</span>' )->add( [new Group()] )->addClass( 'my-toolbar' );
		$expected	= '<div class="btn-toolbar my-toolbar">'.
			'<div class="btn-group" role="group"><button type="button" class="btn">A</button></div>'.
			'<span>B</span>'.
			'<div class="btn-group" role="group"></div>'.
			'</div>';
		self::assertSame( $expected, $toolbar->render(), 'Empty strings are skipped' );
	}
}
