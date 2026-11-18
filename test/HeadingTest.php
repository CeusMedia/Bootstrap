<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Heading;
use PHPUnit\Framework\TestCase;
use RangeException;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Heading
 * @covers			\CeusMedia\Bootstrap\Heading
 */
class HeadingTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<h2 class="autocut"><span class="muted">Company</span> Example Inc. <small>since 2012</small></h2>'],
			'Bootstrap 3'	=> ['3.4.1', '<h2 class="autocut"><span class="text-muted">Company</span> Example Inc. <small>since 2012</small></h2>'],
			'Bootstrap 4'	=> ['4.6.2', '<h2 class="autocut"><span class="text-muted">Company</span> Example Inc. <small class="text-muted">since 2012</small></h2>'],
			'Bootstrap 5'	=> ['5.3.3', '<h2 class="autocut"><span class="text-muted">Company</span> Example Inc. <small class="text-muted">since 2012</small></h2>'],
		];
	}

	/**
	 *	@covers		::__construct
	 *	@covers		::render
	 *	@covers		::setSubtitle
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$heading	= ( new Heading( 'Example Inc.', 2, 'Company', 'autocut' ) )
			->setSubtitle( 'since 2012' )
			->setBsVersion( $version );
		self::assertSame( $expected, $heading->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setLevel
	 *	@covers		::setPrefix
	 *	@return		void
	 */
	public function testRenderPlain(): void
	{
		self::assertSame( '<h2>Title</h2>', ( new Heading( 'Title' ) )->render() );
		self::assertSame( '<h4 id="h">Title</h4>', ( new Heading( 'Title' ) )->setLevel( 4 )->setId( 'h' )->render() );
		self::assertSame( '<h1>Title</h1>', ( new Heading( 'Title', 1, 'Prefix' ) )->setPrefix( NULL )->render() );
	}

	/**
	 *	Prefix and subtitle may be components, rendered for version of heading.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderComponents(): void
	{
		$badge		= new Badge( 'new' );
		$heading	= ( new Heading( ['Title ', $badge], 3 ) )->setSubtitle( $badge )->setBsVersion( '5' );
		$expected	= '<h3>Title <span class="badge bg-secondary rounded-pill">new</span> <small class="text-muted"><span class="badge bg-secondary rounded-pill">new</span></small></h3>';
		self::assertSame( $expected, $heading->render() );
		self::assertSame( BsVersion::V2, $badge->getBsVersion(), 'Given component must not be changed' );
	}

	/**
	 *	@covers		::setLevel
	 *	@return		void
	 */
	public function testSetLevelThrowsOnInvalidLevel(): void
	{
		$this->expectException( RangeException::class );
		new Heading( 'Title', 7 );
	}
}
