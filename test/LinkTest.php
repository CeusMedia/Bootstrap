<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Link;
use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Link
 * @covers			\CeusMedia\Bootstrap\Link
 */
class LinkTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$href	= 'https://example.com/link';
		$label	= 'Link Label';
		$class	= 'btn btn-small btn-danger';
		$link	= new UnprotectedLink( $href, $label, $class );

		self::assertEquals( $href, $link->getUrl() );
		self::assertEquals( $label, $link->getContent() );
		self::assertEquals( explode( ' ', $class ), $link->getClasses() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$link	= new Link( '/path', 'Label' );
		self::assertEquals( '<a href="/path">Label</a>', $link->render() );

		$link	= new Link( '/path', 'Label', 'btn btn-small' );
		self::assertEquals( '<a href="/path" class="btn btn-small">Label</a>', $link->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderDisabled(): void
	{
		$expected	= '<a class="disabled muted" data-attr-href="/path" aria-disabled="true">Label</a>';
		$link		= new Link( '/path', 'Label', NULL, NULL, TRUE );
		self::assertEquals( $expected, $link->render() );
		self::assertEquals( $expected, $link->render(), 'Second rendering must not differ' );

		$expected	= '<a class="btn disabled" data-attr-href="/path" aria-disabled="true">Label</a>';
		$link		= new Link( '/path', 'Label', 'btn', NULL, TRUE );
		self::assertEquals( $expected, $link->render() );

		$link		= new Link( '/path', 'Label', 'btn disabled', NULL, TRUE );
		self::assertEquals( $expected, $link->render(), 'Class "disabled" must not be doubled' );
	}

	/**
	 *	Plain disabled links are muted, links styled by Bootstrap (like buttons) keep their own disabled style.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderDisabledMutedPerVersion(): void
	{
		$expected	= [
			'3'	=> 'disabled text-muted',
			'4'	=> 'disabled text-muted',
			'5'	=> 'disabled text-muted text-decoration-none',
		];
		foreach( $expected as $version => $classes ){
			$link	= ( new Link( '/path', 'Label', NULL, NULL, TRUE ) )->setBsVersion( (string) $version );
			self::assertSame( '<a class="'.$classes.'" data-attr-href="/path" aria-disabled="true">Label</a>', $link->render() );
		}
		$link	= ( new Link( '/path', 'Label', 'nav-link', NULL, TRUE ) )->setBsVersion( '5' );
		self::assertSame( '<a class="nav-link disabled" data-attr-href="/path" aria-disabled="true">Label</a>', $link->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderReenabled(): void
	{
		$link		= new Link( '/path', 'Label', 'btn', NULL, TRUE );
		$link->render();
		$link->setDisabled( FALSE );
		self::assertEquals( '<a href="/path" class="btn">Label</a>', $link->render() );
	}

	/**
	 *	@covers		::__construct
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderWithIcon(): void
	{
		$link	= new Link( '#', 'Label', NULL, 'icon-star' );
		self::assertSame( '<a href="#"><i class="icon-icon-star"></i> Label</a>', $link->render() );
	}

	/**
	 *	Disabled link keeps click events as data attribute, without running them.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderDisabledWithClickEvent(): void
	{
		$link	= ( new Link( '/path', 'Label' ) )->setDisabled()->setEvent( 'click', 'go()' )->setEvent( 'mouseover', 'hint()' );
		$html	= $link->setBsVersion( '3' )->render();
		self::assertSame( '<a class="disabled text-muted" onmouseover="hint()" data-attr-href="/path" data-attr-onclick="go()" aria-disabled="true">Label</a>', $html );
	}
}

class UnprotectedLink extends Link
{
	public function getClasses(): array
	{
		return $this->classes;
	}

	public function getContent(): Renderable|Stringable|array|string|NULL
	{
		return $this->content;
	}

	public function getUrl(): URL|string
	{
		return $this->url;
	}
}
