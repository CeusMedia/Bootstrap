<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Nav;

use CeusMedia\Bootstrap\Nav\NavList;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Nav\NavList
 * @covers			\CeusMedia\Bootstrap\Nav\NavList
 */
class NavListTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<ul class="nav nav-list">'.
				'<li class="nav-header autocut">Topic</li>'.
				'<li class="active" title="A"><a href="#a" class="autocut">A</a></li>'.
				'<li class="divider"></li>'.
				'<li class="my" title="B"><a href="#b" class="autocut">B</a></li>'.
				'</ul>'],
			'Bootstrap 3'	=> ['3.4.1', '<ul class="nav nav-pills nav-stacked">'.
				'<li class="dropdown-header autocut">Topic</li>'.
				'<li class="active" title="A"><a href="#a" class="autocut">A</a></li>'.
				'<li class="nav-divider" role="separator"></li>'.
				'<li class="my" title="B"><a href="#b" class="autocut">B</a></li>'.
				'</ul>'],
			'Bootstrap 4'	=> ['4.6.2', '<ul class="nav nav-pills flex-column">'.
				'<li class="dropdown-header autocut">Topic</li>'.
				'<li class="nav-item" title="A"><a href="#a" class="autocut nav-link active" aria-current="page">A</a></li>'.
				'<li class="dropdown-divider" role="separator"></li>'.
				'<li class="nav-item my" title="B"><a href="#b" class="autocut nav-link">B</a></li>'.
				'</ul>'],
			'Bootstrap 5'	=> ['5.3.3', '<ul class="nav nav-pills flex-column">'.
				'<li class="px-4 py-2 small text-muted autocut">Topic</li>'.
				'<li class="nav-item" title="A"><a href="#a" class="autocut nav-link active" aria-current="page">A</a></li>'.
				'<li class="border-top my-2" role="separator"></li>'.
				'<li class="nav-item my" title="B"><a href="#b" class="autocut nav-link">B</a></li>'.
				'</ul>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderDivider
	 *	@covers		::renderHeader
	 *	@covers		::renderLink
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$list	= ( new NavList() )
			->setBsVersion( $version )
			->addHeader( 'Topic' )
			->add( '#a', 'A' )
			->addDivider()
			->add( '#b', 'B', NULL, 'my' )
			->setCurrent( '#a' );
		self::assertSame( $expected, $list->render() );
	}

	/**
	 *	@covers		::renderLink
	 *	@return		void
	 */
	public function testRenderActiveByClass(): void
	{
		$list	= ( new NavList() )->setBsVersion( '5' )->add( '#a', 'A', NULL, 'active' );
		self::assertStringContainsString( '<li class="nav-item" title="A"><a href="#a" class="autocut nav-link active" aria-current="page">A</a></li>', $list->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::addNavList
	 *	@return		void
	 */
	public function testRenderNestedListUsesSameVersion(): void
	{
		$inner	= ( new NavList() )->add( '#x', 'X' );
		$outer	= ( new NavList() )->setBsVersion( '3' )->addNavList( $inner );
		self::assertSame( '<ul class="nav nav-pills nav-stacked"><ul class="nav nav-pills nav-stacked"><li title="X"><a href="#x" class="autocut">X</a></li></ul></ul>', $outer->render() );
	}

	/**
	 *	@covers		::renderHeader
	 *	@return		void
	 */
	public function testRenderHeaderWithIcon(): void
	{
		$list	= ( new NavList() )->setBsVersion( '2' )->addHeader( 'Topic', 'icon-star', 'my-header' );
		self::assertSame( '<ul class="nav nav-list"><li class="nav-header autocut my-header"><i class="icon-icon-star"></i> Topic</li></ul>', $list->render() );
	}
}
