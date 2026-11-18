<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Nav;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Nav\PageControl;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Nav\PageControl
 * @covers			\CeusMedia\Bootstrap\Nav\PageControl
 */
class PageControlTest extends TestCase
{
	protected string $defaultIconSet;

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		$items	= [
			'<li%1$s><a href="#page-0"%2$s><i class="fa fa-fast-backward"></i></a></li>',
			'<li%1$s><a href="#page-0"%2$s><i class="fa fa-backward"></i></a></li>',
			'<li%3$s><span%2$s><b>2</b> / 3</span></li>',
			'<li%1$s><a href="#page-2"%2$s><i class="fa fa-forward"></i></a></li>',
			'<li%1$s><a href="#page-2"%2$s><i class="fa fa-fast-forward"></i></a></li>',
		];
		$legacy	= sprintf( join( $items ), '', '', ' class="active"' );
		$modern	= sprintf( join( $items ), ' class="page-item"', ' class="page-link"', ' class="page-item active" aria-current="page"' );
		return [
			'Bootstrap 2'	=> ['2.3.2', '<div class="pagination page-control"><ul>'.$legacy.'</ul></div>'],
			'Bootstrap 3'	=> ['3.4.1', '<nav aria-label="Pagination"><ul class="pagination page-control">'.str_replace( 'class="active"', 'class="active" aria-current="page"', $legacy ).'</ul></nav>'],
			'Bootstrap 4'	=> ['4.6.2', '<nav aria-label="Pagination"><ul class="pagination page-control">'.$modern.'</ul></nav>'],
			'Bootstrap 5'	=> ['5.3.3', '<nav aria-label="Pagination"><ul class="pagination page-control">'.$modern.'</ul></nav>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderItem
	 *	@covers		::getUrl
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$control	= ( new PageControl( '#page-', 1, 3 ) )->setBsVersion( $version );
		$control->patternUrl	= '%s';
		self::assertSame( $expected, $control->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string}>
	 */
	public static function disabledProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<li class="disabled"><span><i class="fa fa-fast-backward"></i></span></li>', '<li class="disabled"><span><i class="fa fa-fast-forward"></i></span></li>'],
			'Bootstrap 5'	=> ['5.3.3', '<li class="page-item disabled"><span class="page-link"><i class="fa fa-fast-backward"></i></span></li>', '<li class="page-item disabled"><span class="page-link"><i class="fa fa-fast-forward"></i></span></li>'],
		];
	}

	/**
	 *	Links to first and previous page are disabled on first page, links to next and last page on last page.
	 *	@covers		::render
	 *	@covers		::renderItem
	 *	@dataProvider	disabledProvider
	 *	@return		void
	 */
	public function testRenderDisabledItems( string $version, string $expectedFirst, string $expectedLast ): void
	{
		$first	= ( new PageControl( '#page-', 0, 3 ) )->setBsVersion( $version )->render();
		self::assertStringContainsString( $expectedFirst, $first );
		self::assertStringNotContainsString( $expectedLast, $first );

		$last	= ( new PageControl( '#page-', 2, 3 ) )->setBsVersion( $version )->render();
		self::assertStringContainsString( $expectedLast, $last );
		self::assertStringNotContainsString( $expectedFirst, $last );
	}

	/**
	 *	@return		array<string,array{string,string,string,string}>
	 */
	public static function sizeProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'pagination-mini', 'pagination-small', 'pagination-large'],
			'Bootstrap 3'	=> ['3.4.1', 'pagination-sm', 'pagination-sm', 'pagination-lg'],
			'Bootstrap 4'	=> ['4.6.2', 'pagination-sm', 'pagination-sm', 'pagination-lg'],
			'Bootstrap 5'	=> ['5.3.3', 'pagination-sm', 'pagination-sm', 'pagination-lg'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::realizeSizeClass
	 *	@dataProvider	sizeProvider
	 *	@return		void
	 */
	public function testRenderWithSize( string $version, string $expectedMini, string $expectedSmall, string $expectedLarge ): void
	{
		$expected	= [
			PageControl::SIZE_MINI		=> $expectedMini,
			PageControl::SIZE_SMALL		=> $expectedSmall,
			PageControl::SIZE_LARGE		=> $expectedLarge,
		];
		foreach( $expected as $size => $class ){
			$control	= ( new PageControl( '#page-', 0, 3 ) )->setBsVersion( $version );
			$control->size	= $size;
			self::assertStringContainsString( 'class="pagination '.$class.' page-control"', $control->render() );
		}
	}

	/**
	 *	@covers		::render
	 *	@covers		::getUrl
	 *	@return		void
	 */
	public function testRenderUrls(): void
	{
		$control	= ( new PageControl( './list', 3, 10 ) )->setBsVersion( '5' );
		$control->fragment	= 'results';
		$html	= $control->render();
		self::assertStringContainsString( 'href="./list#results"', $html, 'First page without page number' );
		self::assertStringContainsString( 'href="./list/2#results"', $html );
		self::assertStringContainsString( 'href="./list/4#results"', $html );
		self::assertStringContainsString( 'href="./list/9#results"', $html );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderSinglePage(): void
	{
		self::assertSame( '', ( new PageControl( '#page-', 0, 1 ) )->render() );
	}

	protected function setUp(): void
	{
		$this->defaultIconSet	= Icon::$defaultSet;
		Icon::$defaultSet		= 'fontawesome';
	}

	protected function tearDown(): void
	{
		Icon::$defaultSet		= $this->defaultIconSet;
	}
}
