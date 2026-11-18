<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Nav;

use CeusMedia\Bootstrap\Dropdown\Menu;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Nav\Pills;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Nav\Pills
 * @covers			\CeusMedia\Bootstrap\Nav\Pills
 * @covers			\CeusMedia\Bootstrap\Base\DataObject\NavPillItemLink
 * @covers			\CeusMedia\Bootstrap\Base\DataObject\NavPillItemDropdown
 */
class PillsTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<ul class="nav nav-pills">'.
				'<li><a href="#a">A</a></li>'.
				'<li class="active"><a href="#b">B</a></li>'.
				'<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">C <span class="caret"></span></a>'.
				'<ul class="dropdown-menu"><li><a href="#x">X</a></li></ul></li>'.
				'</ul>'],
			'Bootstrap 3'	=> ['3.4.1', '<ul class="nav nav-pills">'.
				'<li><a href="#a">A</a></li>'.
				'<li class="active"><a href="#b">B</a></li>'.
				'<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">C <span class="caret"></span></a>'.
				'<ul class="dropdown-menu"><li><a href="#x">X</a></li></ul></li>'.
				'</ul>'],
			'Bootstrap 4'	=> ['4.6.2', '<ul class="nav nav-pills">'.
				'<li class="nav-item"><a href="#a" class="nav-link">A</a></li>'.
				'<li class="nav-item"><a href="#b" class="nav-link active" aria-current="page">B</a></li>'.
				'<li class="nav-item dropdown"><a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">C</a>'.
				'<div class="dropdown-menu"><a href="#x" class="dropdown-item">X</a></div></li>'.
				'</ul>'],
			'Bootstrap 5'	=> ['5.3.3', '<ul class="nav nav-pills">'.
				'<li class="nav-item"><a href="#a" class="nav-link">A</a></li>'.
				'<li class="nav-item"><a href="#b" class="nav-link active" aria-current="page">B</a></li>'.
				'<li class="nav-item dropdown"><a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">C</a>'.
				'<ul class="dropdown-menu"><li><a href="#x" class="dropdown-item">X</a></li></ul></li>'.
				'</ul>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderLinkItem
	 *	@covers		::renderDropdownItem
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$pills	= $this->createPills()->setBsVersion( $version )->setActive( 1 );
		self::assertSame( $expected, $pills->render() );
		self::assertSame( $expected, $pills->render(), 'Second rendering must not differ' );
	}

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function activeDropdownProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle'],
			'Bootstrap 3'	=> ['3.4.1', '<li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle'],
			'Bootstrap 4'	=> ['4.6.2', '<li class="nav-item dropdown"><a href="#" class="dropdown-toggle nav-link active" data-toggle'],
			'Bootstrap 5'	=> ['5.3.3', '<li class="nav-item dropdown"><a href="#" class="dropdown-toggle nav-link active" data-bs-toggle'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderDropdownItem
	 *	@dataProvider	activeDropdownProvider
	 *	@return		void
	 */
	public function testRenderActiveDropdown( string $version, string $expected ): void
	{
		$pills	= $this->createPills()->setBsVersion( $version )->setActive( 2 );
		self::assertStringContainsString( $expected, $pills->render() );
	}

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function stackedProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'nav nav-pills nav-stacked'],
			'Bootstrap 3'	=> ['3.4.1', 'nav nav-pills nav-stacked'],
			'Bootstrap 4'	=> ['4.6.2', 'nav nav-pills flex-column'],
			'Bootstrap 5'	=> ['5.3.3', 'nav nav-pills flex-column'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setStacked
	 *	@dataProvider	stackedProvider
	 *	@return		void
	 */
	public function testRenderStacked( string $version, string $expectedClass ): void
	{
		$pills	= $this->createPills()->setBsVersion( $version )->setStacked();
		self::assertStringStartsWith( '<ul class="'.$expectedClass.'">', $pills->render() );
	}

	protected function createPills(): Pills
	{
		return ( new Pills() )
			->add( '#a', 'A' )
			->addLink( new Link( '#b', 'B' ) )
			->addDropdown( ( new Menu() )->add( '#x', 'X' ), 'C' );
	}
}
