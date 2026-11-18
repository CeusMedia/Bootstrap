<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Card;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Card
 * @covers			\CeusMedia\Bootstrap\Card
 * @covers			\CeusMedia\Bootstrap\Base\StateClassParser
 */
class CardTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<div class="well"><h4>Header</h4><h5>Title</h5>Body<hr/><div class="muted">Footer</div></div>'],
			'Bootstrap 3'	=> ['3.4.1', '<div class="panel panel-default"><div class="panel-heading">Header</div><div class="panel-body"><h4>Title</h4>Body</div><div class="panel-footer">Footer</div></div>'],
			'Bootstrap 4'	=> ['4.6.2', '<div class="card mb-3"><div class="card-header">Header</div><div class="card-body"><h5 class="card-title">Title</h5>Body</div><div class="card-footer">Footer</div></div>'],
			'Bootstrap 5'	=> ['5.3.3', '<div class="card mb-3"><div class="card-header">Header</div><div class="card-body"><h5 class="card-title">Title</h5>Body</div><div class="card-footer">Footer</div></div>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setFooter
	 *	@covers		::setTitle
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$card	= ( new Card( 'Body', 'Header' ) )->setTitle( 'Title' )->setFooter( 'Footer' )->setBsVersion( $version );
		self::assertSame( $expected, $card->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string,string}>
	 */
	public static function colorProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'alert alert-error', 'alert', '<h4>Header</h4>'],
			'Bootstrap 3'	=> ['3.4.1', 'panel panel-danger', 'panel panel-warning', '<div class="panel-heading">Header</div>'],
			'Bootstrap 4'	=> ['4.6.2', 'card mb-3 border-danger', 'card mb-3 border-warning', '<div class="card-header bg-danger text-white">Header</div>'],
			'Bootstrap 5'	=> ['5.3.3', 'card mb-3 border-danger', 'card mb-3 border-warning', '<div class="card-header bg-danger text-white">Header</div>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderV2
	 *	@covers		::renderV3
	 *	@covers		::renderV4
	 *	@dataProvider	colorProvider
	 *	@return		void
	 */
	public function testRenderColors( string $version, string $expectedDanger, string $expectedWarning, string $expectedHeader ): void
	{
		$danger	= ( new Card( 'Body', 'Header', Card::CLASS_DANGER ) )->setBsVersion( $version )->render();
		self::assertStringStartsWith( '<div class="'.$expectedDanger.'">'.$expectedHeader, $danger );

		$warning	= ( new Card( 'Body', NULL, 'border-warning' ) )->setBsVersion( $version )->render();
		self::assertStringStartsWith( '<div class="'.$expectedWarning.'">', $warning, 'Color class of Bootstrap 4' );
	}

	/**
	 *	Header on light background gets dark text.
	 *	@covers		::renderV4
	 *	@return		void
	 */
	public function testRenderLightHeader(): void
	{
		$card	= ( new Card( 'Body', 'Header', Card::CLASS_WARNING ) )->setBsVersion( '4' );
		self::assertStringContainsString( '<div class="card-header bg-warning text-dark">', $card->render() );

		$card	= ( new Card( 'Body', 'Header', Card::CLASS_INFO ) );
		self::assertStringContainsString( 'bg-info text-white', $card->setBsVersion( '4' )->render(), 'Info is dark in Bootstrap 4' );
		self::assertStringContainsString( 'bg-info text-dark', $card->setBsVersion( '5' )->render(), 'Info is light in Bootstrap 5' );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderMinimal(): void
	{
		self::assertSame( '<div class="card mb-3"><div class="card-body">Body</div></div>', ( new Card( 'Body' ) )->setBsVersion( '5' )->render() );
		self::assertSame( '<div class="well">Body</div>', ( new Card( 'Body' ) )->render() );
		self::assertSame( '<div id="c" class="panel panel-default my-card" data-topic="x"><div class="panel-body">Body</div></div>', ( new Card( 'Body', NULL, 'my-card' ) )->setBsVersion( '3' )->setId( 'c' )->setData( 'topic', 'x' )->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setHeader
	 *	@return		void
	 */
	public function testRenderComponents(): void
	{
		$badge	= new Badge( '3' );
		$card	= ( new Card( ['List ', $badge] ) )->setHeader( $badge )->setBsVersion( '5' );
		self::assertSame( '<div class="card mb-3"><div class="card-header"><span class="badge bg-secondary rounded-pill">3</span></div><div class="card-body">List <span class="badge bg-secondary rounded-pill">3</span></div></div>', $card->render() );
		self::assertSame( BsVersion::V2, $badge->getBsVersion(), 'Given component must not be changed' );
	}
}
