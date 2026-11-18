<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Dropdown;

use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Dropdown\Menu;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Dropdown\Menu
 * @covers			\CeusMedia\Bootstrap\Dropdown\Menu
 */
class MenuTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<ul class="dropdown-menu">'.
				'<li><a href="#a">A</a></li>'.
				'<li class="divider"></li>'.
				'<li class="disabled"><a href="#b">B</a></li>'.
				'</ul>'],
			'Bootstrap 3'	=> ['3.4.1', '<ul class="dropdown-menu">'.
				'<li><a href="#a">A</a></li>'.
				'<li role="separator" class="divider"></li>'.
				'<li class="disabled"><a href="#b">B</a></li>'.
				'</ul>'],
			'Bootstrap 4'	=> ['4.6.2', '<div class="dropdown-menu">'.
				'<a href="#a" class="dropdown-item">A</a>'.
				'<div class="dropdown-divider"></div>'.
				'<a href="#b" class="dropdown-item disabled">B</a>'.
				'</div>'],
			'Bootstrap 5'	=> ['5.3.3', '<ul class="dropdown-menu">'.
				'<li><a href="#a" class="dropdown-item">A</a></li>'.
				'<li><hr class="dropdown-divider"/></li>'.
				'<li><a href="#b" class="dropdown-item disabled">B</a></li>'.
				'</ul>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderDivider
	 *	@covers		::renderLinkItem
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$menu	= $this->createMenu()->setBsVersion( $version );
		self::assertSame( $expected, $menu->render() );
		self::assertSame( $expected, $menu->render(), 'Second rendering must not differ' );
	}

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function alignRightProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'dropdown-menu pull-right'],
			'Bootstrap 3'	=> ['3.4.1', 'dropdown-menu dropdown-menu-right'],
			'Bootstrap 4'	=> ['4.6.2', 'dropdown-menu dropdown-menu-right'],
			'Bootstrap 5'	=> ['5.3.3', 'dropdown-menu dropdown-menu-end'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setAlign
	 *	@dataProvider	alignRightProvider
	 *	@return		void
	 */
	public function testRenderAlignedRight( string $version, string $expectedClass ): void
	{
		$menu	= $this->createMenu()->setBsVersion( $version )->setAlign( FALSE );
		self::assertStringContainsString( 'class="'.$expectedClass.'"', $menu->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setAriaLabel
	 *	@return		void
	 */
	public function testRenderWithAriaLabel(): void
	{
		$menu	= $this->createMenu()->setBsVersion( '5' )->setAriaLabel( 'Actions' );
		self::assertStringStartsWith( '<ul class="dropdown-menu" aria-label="Actions">', $menu->render() );
	}

	protected function createMenu(): Menu
	{
		return ( new Menu() )
			->add( '#a', 'A' )
			->addDivider()
			->add( '#b', 'B', NULL, NULL, TRUE );
	}

	/**
	 *	@covers		::addLink
	 *	@covers		::render
	 *	@return		void
	 */
	public function testAddLink(): void
	{
		$menu	= ( new Menu() )->setBsVersion( '5' )->addLink( new Link( '#a', 'A' ), TRUE );
		self::assertSame( '<ul class="dropdown-menu"><li><a href="#a" class="dropdown-item disabled">A</a></li></ul>', $menu->render() );
	}

	/**
	 *	Nested dropdowns are not supported anymore, adding one triggers a deprecation and is ignored.
	 *	@covers		::addDropdown
	 *	@return		void
	 */
	public function testAddDropdownIsDeprecated(): void
	{
		$levels	= [];
		set_error_handler( static function( int $level ) use ( &$levels ): bool {
			$levels[]	= $level;
			return TRUE;
		} );
		try{
			$menu	= ( new Menu() )->addDropdown( 'Sub', new Menu() );
		}
		finally{
			restore_error_handler();
		}
		self::assertSame( [E_USER_DEPRECATED], $levels );
		self::assertSame( '<ul class="dropdown-menu"></ul>', $menu->render() );
	}
}
