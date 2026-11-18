<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Row
 * @covers			\CeusMedia\Bootstrap\Row
 */
class RowTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'row', 'row-fluid', 'span4'],
			'Bootstrap 3'	=> ['3.4.1', 'row', 'row', 'col-md-4'],
			'Bootstrap 4'	=> ['4.6.2', 'row', 'row', 'col-md-4'],
			'Bootstrap 5'	=> ['5.3.3', 'row', 'row', 'col-md-4'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setFluid
	 *	@covers		\CeusMedia\Bootstrap\RowSpan::render
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $row, string $rowFluid, string $span ): void
	{
		$spanHtml	= ( new RowSpan( 'A' ) )->setSize( 4 )->setBsVersion( $version )->render();
		self::assertSame( '<div class="'.$span.'">A</div>', $spanHtml );

		$rowHtml	= ( new Row( $spanHtml ) )->setBsVersion( $version )->render();
		self::assertSame( '<div class="'.$row.'">'.$spanHtml.'</div>', $rowHtml );

		$rowHtml	= ( new Row( $spanHtml, 'my-row' ) )->setFluid( TRUE )->setBsVersion( $version )->render();
		self::assertSame( '<div class="'.$rowFluid.' my-row">'.$spanHtml.'</div>', $rowHtml );
	}

	/**
	 *	Content of any component may be a list of strings and components,
	 *	which are rendered for the version of the containing component.
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\ContentAware::getContentAsString
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\ContentAware::renderContentItem
	 *	@return		void
	 */
	public function testRenderListContent(): void
	{
		$badge	= new Badge( '1' );
		$span	= ( new RowSpan( ['<label>A</label>', $badge, 2] ) )->setSize( 6 )->setBsVersion( '5' );
		self::assertSame( '<div class="col-md-6"><label>A</label><span class="badge bg-secondary rounded-pill">1</span>2</div>', $span->render() );
		self::assertSame( BsVersion::V2, $badge->getBsVersion(), 'Given component must not be changed' );
	}

	/**
	 *	@covers		::render
	 *	@covers		\CeusMedia\Bootstrap\RowSpan::render
	 *	@return		void
	 */
	public function testRenderAttributes(): void
	{
		$span	= ( new RowSpan( 'A' ) )->setSize( 3 )->setId( 'layout-list' )->setData( 'topic', 'news' )->setBsVersion( '5' );
		self::assertSame( '<div id="layout-list" class="col-md-3" data-topic="news">A</div>', $span->render() );

		$row	= ( new Row( [$span] ) )->setId( 'layout' )->setBsVersion( '2' );
		self::assertSame( '<div id="layout" class="row"><div id="layout-list" class="span3" data-topic="news">A</div></div>', $row->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		\CeusMedia\Bootstrap\Base\Aware\ContentAware::getContentAsString
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderListOfSpansForRowVersion( string $version, string $row, string $rowFluid, string $span ): void
	{
		$spans	= [
			( new RowSpan( 'A', 'first' ) )->setSize( 4 ),
			( new RowSpan( 'B' ) )->setSize( 4 ),
		];
		$html	= ( new Row( $spans ) )->setBsVersion( $version )->render();
		self::assertSame( '<div class="'.$row.'"><div class="'.$span.' first">A</div><div class="'.$span.'">B</div></div>', $html );
		self::assertSame( BsVersion::V2, $spans[0]->getBsVersion(), 'Given spans must not be changed' );
	}
}
