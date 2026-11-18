<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Nav;

use CeusMedia\Bootstrap\Nav\TabbableNavbar;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Nav\TabbableNavbar
 * @covers			\CeusMedia\Bootstrap\Nav\TabbableNavbar
 */
class TabbableNavbarTest extends TestCase
{
	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderMarksFirstTabActiveByDefault(): void
	{
		$html	= $this->createNavbar()->render();
		self::assertStringContainsString( '<li class="active"><a href="#tab-a"', $html );
		self::assertStringContainsString( '<div id="tab-a" class="tab-pane active">', $html );
		self::assertStringContainsString( '<div id="tab-b" class="tab-pane">', $html );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setActive
	 *	@return		void
	 */
	public function testRenderMarksTabActiveByNumber(): void
	{
		$html	= $this->createNavbar()->setActive( 1 )->render();
		self::assertStringContainsString( '<li class="active"><a href="#tab-b"', $html );
		self::assertStringContainsString( '<div id="tab-b" class="tab-pane active">', $html );
		self::assertStringContainsString( '<div id="tab-a" class="tab-pane">', $html );
	}

	/**
	 *	@return		array<string,array{string,list<string>}>
	 */
	public static function versionProvider(): array
	{
		$collapseId	= 'navbar-collapse-'.substr( md5( 'tab-a,tab-b' ), 0, 8 );
		return [
			'Bootstrap 2'	=> ['2.3.2', [
				'<div class="tabbable"><div class="navbar navbar-fixed-top"><div class="navbar-inner"><div class="container">',
				'<a data-toggle="collapse" data-target=".nav-collapse" class="btn btn-navbar">',
				'<a href="#" class="brand">Brand</a>',
				'<div class="nav-collapse collapse"><ul class="nav"><li><a href="#tab-a" data-toggle="tab">A</a></li><li class="active"><a href="#tab-b" data-toggle="tab">B</a></li></ul></div>',
			]],
			'Bootstrap 3'	=> ['3.4.1', [
				'<div class="tabbable"><nav class="navbar navbar-default navbar-fixed-top"><div class="container-fluid"><div class="navbar-header">',
				'<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#'.$collapseId.'" aria-expanded="false">',
				'<a href="#" class="navbar-brand">Brand</a></div>',
				'<div class="collapse navbar-collapse" id="'.$collapseId.'"><ul class="nav navbar-nav" role="tablist"><li><a href="#tab-a" data-toggle="tab">A</a></li><li class="active"><a href="#tab-b" data-toggle="tab">B</a></li></ul></div>',
			]],
			'Bootstrap 4'	=> ['4.6.2', [
				'<div class="tabbable"><nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"><a href="#" class="navbar-brand">Brand</a>',
				'<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#'.$collapseId.'" aria-controls="'.$collapseId.'"',
				'<li class="nav-item"><a href="#tab-b" class="nav-link active" data-toggle="tab" role="tab">B</a></li>',
			]],
			'Bootstrap 5'	=> ['5.3.3', [
				'<div class="tabbable"><nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"><div class="container-fluid"><a href="#" class="navbar-brand">Brand</a>',
				'<button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#'.$collapseId.'" aria-controls="'.$collapseId.'"',
				'<li class="nav-item"><a href="#tab-b" class="nav-link active" data-bs-toggle="tab" role="tab">B</a></li>',
			]],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setBrand
	 *	@covers		::setFixed
	 *	@dataProvider	versionProvider
	 *	@param		list<string>	$expectedParts
	 *	@return		void
	 */
	public function testRenderForVersion( string $version, array $expectedParts ): void
	{
		$html	= ( new TabbableNavbar() )
			->setBsVersion( $version )
			->setBrand( 'Brand', '#' )
			->setFixed( 'top' )
			->add( 'tab-a', 'A', 'Content A' )
			->add( 'tab-b', 'B', 'Content B' )
			->setActive( 1 )
			->render();
		foreach( $expectedParts as $part )
			self::assertStringContainsString( $part, $html );
		self::assertStringEndsWith( '<div class="tab-content"><div id="tab-a" class="tab-pane">Content A</div><div id="tab-b" class="tab-pane active">Content B</div></div></div>', $html );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setBrand
	 *	@return		void
	 */
	public function testRenderBrandWithoutUrl(): void
	{
		$html	= $this->createNavbar()->setBsVersion( '5' )->setBrand( 'Brand' )->render();
		self::assertStringContainsString( '<span class="navbar-brand">Brand</span>', $html );
	}

	protected function createNavbar(): TabbableNavbar
	{
		$navbar	= new TabbableNavbar();
		$navbar->add( 'tab-a', 'Tab A', 'Content A' );
		$navbar->add( 'tab-b', 'Tab B', 'Content B' );
		return $navbar;
	}
}
