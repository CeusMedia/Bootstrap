<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Nav;

use RangeException;
use CeusMedia\Bootstrap\Nav\Tabs;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Nav\Tabs
 * @covers			\CeusMedia\Bootstrap\Nav\Tabs
 * @covers			\CeusMedia\Bootstrap\Base\DataObject\NavTabsItem
 */
class TabsTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,non-empty-string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<nav><ul class="nav nav-tabs" id="tabs" role="tablist">'.
				'<li><a href="#a" data-toggle="tab">A</a></li>'.
				'<li class="active"><a href="#b" data-toggle="tab">B</a></li>'.
				'<li><a href="/other">C</a></li>'.
				'<li class="disabled"><a>D</a></li>'.
				'</ul></nav>'],
			'Bootstrap 3'	=> ['3.4.1', '<nav><ul class="nav nav-tabs" id="tabs" role="tablist">'.
				'<li role="presentation"><a href="#a" role="tab" aria-controls="a" data-toggle="tab">A</a></li>'.
				'<li class="active" role="presentation"><a href="#b" role="tab" aria-controls="b" data-toggle="tab">B</a></li>'.
				'<li role="presentation"><a href="/other">C</a></li>'.
				'<li class="disabled" role="presentation"><a role="tab" aria-controls="d">D</a></li>'.
				'</ul></nav>'],
			'Bootstrap 4'	=> ['4.6.2', '<nav><ul class="nav nav-tabs" id="tabs" role="tablist">'.
				'<li class="nav-item"><a class="nav-link" href="#a" role="tab" aria-controls="a" aria-selected="false" data-toggle="tab">A</a></li>'.
				'<li class="nav-item"><a class="nav-link active" href="#b" role="tab" aria-controls="b" aria-selected="true" data-toggle="tab">B</a></li>'.
				'<li class="nav-item"><a class="nav-link" href="/other">C</a></li>'.
				'<li class="nav-item"><a class="nav-link disabled" role="tab" aria-controls="d" aria-selected="false" aria-disabled="true">D</a></li>'.
				'</ul></nav>'],
			'Bootstrap 5'	=> ['5.3.3', '<nav><ul class="nav nav-tabs" id="tabs" role="tablist">'.
				'<li class="nav-item"><a class="nav-link" href="#a" role="tab" aria-controls="a" aria-selected="false" data-bs-toggle="tab">A</a></li>'.
				'<li class="nav-item"><a class="nav-link active" href="#b" role="tab" aria-controls="b" aria-selected="true" data-bs-toggle="tab">B</a></li>'.
				'<li class="nav-item"><a class="nav-link" href="/other">C</a></li>'.
				'<li class="nav-item"><a class="nav-link disabled" role="tab" aria-controls="d" aria-selected="false" aria-disabled="true">D</a></li>'.
				'</ul></nav>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderTab
	 *	@dataProvider	versionProvider
	 *	@param		non-empty-string	$expected
	 *	@return		void
	 */
	public function testRenderTabs( string $version, string $expected ): void
	{
		$html	= $this->createTabs()->setBsVersion( $version )->setActive( 'b' )->render();
		self::assertStringStartsWith( $expected, $html );
	}

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function paneProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'tab-pane active'],
			'Bootstrap 3'	=> ['3.4.1', 'tab-pane active'],
			'Bootstrap 4'	=> ['4.6.2', 'tab-pane show active'],
			'Bootstrap 5'	=> ['5.3.3', 'tab-pane show active'],
		];
	}

	/**
	 *	@covers		::render
	 *	@dataProvider	paneProvider
	 *	@return		void
	 */
	public function testRenderPanes( string $version, string $expectedActiveClass ): void
	{
		$html	= $this->createTabs()->setBsVersion( $version )->setActive( 1 )->render();
		self::assertStringContainsString( '<div class="tab-pane" id="a" role="tabpanel">Content A</div>', $html );
		self::assertStringContainsString( '<div class="'.$expectedActiveClass.'" id="b" role="tabpanel">Content B</div>', $html );
	}

	/**
	 *	@covers		::render
	 *	@covers		::getFirstEnabledId
	 *	@return		void
	 */
	public function testRenderActivatesFirstEnabledTabWithoutChangingState(): void
	{
		$tabs	= ( new Tabs( 'tabs' ) )
			->setBsVersion( '5' )
			->add( 'a', '#a', 'A', 'Content A', TRUE )
			->add( 'b', '#b', 'B', 'Content B' );
		$html	= $tabs->render();
		self::assertStringContainsString( '<a class="nav-link active" href="#b"', $html );

		$tabs->enableTab( 'a' );
		self::assertStringContainsString( '<a class="nav-link active" href="#a"', $tabs->render(), 'Active tab must not be fixed by rendering' );
	}

	/**
	 *	@covers		::setActive
	 *	@return		void
	 */
	public function testSetActiveThrowsOnDisabledTab(): void
	{
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Tab with ID d is disabled and cannot be active' );
		$this->createTabs()->setActive( 'd' );
	}

	protected function createTabs(): Tabs
	{
		return ( new Tabs( 'tabs' ) )
			->add( 'a', '#a', 'A', 'Content A' )
			->add( 'b', '#b', 'B', 'Content B' )
			->add( 'c', '/other', 'C' )
			->add( 'd', '#d', 'D', 'Content D', TRUE );
	}

	/**
	 *	@covers		::disableTab
	 *	@covers		::enableTab
	 *	@covers		::getIdByIndex
	 *	@return		void
	 */
	public function testDisableAndEnableTab(): void
	{
		$tabs	= $this->createTabs()->setBsVersion( '5' );
		$tabs->disableTab( 0 )->disableTab( 'b' );
		self::assertStringContainsString( '<a class="nav-link disabled" role="tab" aria-controls="a"', $tabs->render() );
		self::assertStringContainsString( '<a class="nav-link disabled" role="tab" aria-controls="b"', $tabs->render() );

		$tabs->enableTab( 1 );
		self::assertStringContainsString( '<a class="nav-link active" href="#b"', $tabs->render(), 'First enabled tab is active' );
	}

	/**
	 *	@covers		::render
	 *	@covers		::getFirstEnabledId
	 *	@return		void
	 */
	public function testRenderWithAllTabsDisabled(): void
	{
		$tabs	= ( new Tabs( 'tabs' ) )->setBsVersion( '5' )->add( 'a', '#a', 'A', 'Content A', TRUE );
		self::assertStringNotContainsString( 'active', $tabs->render() );
	}

	/**
	 *	@covers		::setActive
	 *	@covers		::getIdByIndex
	 *	@covers		::getTabById
	 *	@return		void
	 */
	public function testSetActiveThrowsOnUnknownTab(): void
	{
		try{
			$this->createTabs()->setActive( 9 );
			self::fail( 'Exception expected for unknown index' );
		}
		catch( RangeException ){
		}
		$this->expectException( RangeException::class );
		$this->expectExceptionMessage( 'No tab available for ID x' );
		$this->createTabs()->setActive( 'x' );
	}
}
