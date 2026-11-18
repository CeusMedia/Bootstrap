<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Nav;

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Nav\Breadcrumbs;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Nav\Breadcrumbs
 * @covers			\CeusMedia\Bootstrap\Nav\Breadcrumbs
 */
class BreadcrumbsTest extends TestCase
{
	protected string $defaultIconSet;

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<nav><ul class="breadcrumb">'.
				'<li><a href="#home">Home</a> <span class="divider">/</span></li>'.
				'<li><a href="#lib"><i class="fa fa-folder"></i> Library</a> <span class="divider">/</span></li>'.
				'<li class="active">Demo</li>'.
				'</ul></nav>'],
			'Bootstrap 3'	=> ['3.4.1', '<nav><ol class="breadcrumb">'.
				'<li><a href="#home">Home</a></li>'.
				'<li><a href="#lib"><i class="fa fa-folder"></i> Library</a></li>'.
				'<li class="active">Demo</li>'.
				'</ol></nav>'],
			'Bootstrap 4'	=> ['4.6.2', '<nav aria-label="breadcrumb"><ol class="breadcrumb">'.
				'<li class="breadcrumb-item"><a href="#home">Home</a></li>'.
				'<li class="breadcrumb-item"><a href="#lib"><i class="fa fa-folder"></i> Library</a></li>'.
				'<li class="breadcrumb-item active" aria-current="page">Demo</li>'.
				'</ol></nav>'],
			'Bootstrap 5'	=> ['5.3.3', '<nav aria-label="breadcrumb"><ol class="breadcrumb">'.
				'<li class="breadcrumb-item"><a href="#home">Home</a></li>'.
				'<li class="breadcrumb-item"><a href="#lib"><i class="fa fa-folder"></i> Library</a></li>'.
				'<li class="breadcrumb-item active" aria-current="page">Demo</li>'.
				'</ol></nav>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderCrumbContent
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected ): void
	{
		$crumbs	= ( new Breadcrumbs() )
			->setBsVersion( $version )
			->addLink( new Link( '#home', 'Home' ) )
			->add( 'Library', '#lib', NULL, new Icon( 'folder' ) )
			->addCurrent( 'Demo' );
		self::assertSame( $expected, $crumbs->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setDivider
	 *	@return		void
	 */
	public function testRenderCustomDivider(): void
	{
		$crumbs	= ( new Breadcrumbs( '>' ) )->add( 'A', '#a' )->addCurrent( 'B' );
		self::assertStringContainsString( '<span class="divider">&gt;</span>', $crumbs->setBsVersion( '2' )->render() );
		self::assertStringStartsWith( '<nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: &#039;&gt;&#039;;">', $crumbs->setBsVersion( '5' )->render() );
		self::assertStringStartsWith( '<nav aria-label="breadcrumb"><ol', $crumbs->setBsVersion( '4' )->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderWithClass(): void
	{
		$crumbs	= ( new Breadcrumbs( '/', 'my-crumbs' ) )->setBsVersion( '5' )->addCurrent( 'A' );
		self::assertStringContainsString( '<ol class="breadcrumb my-crumbs">', $crumbs->render() );
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

	/**
	 *	@covers		::renderCrumbContent
	 *	@return		void
	 */
	public function testRenderClassAndIconWithoutUrl(): void
	{
		$crumbs	= ( new Breadcrumbs() )->setBsVersion( '5' )->add( 'A', NULL, 'my-crumb', 'home' );
		self::assertStringContainsString( '<li class="breadcrumb-item my-crumb"><i class="fa fa-home"></i> A</li>', $crumbs->render() );
	}
}
