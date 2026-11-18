<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Button;

use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Button\Link as ButtonLink;
use CeusMedia\Bootstrap\Icon;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Button\Link
 * @covers			\CeusMedia\Bootstrap\Button\Link
 */
class LinkTest extends TestCase
{
	protected string $defaultIconSet;

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'btn btn-small'],
			'Bootstrap 3'	=> ['3.4.1', 'btn btn-default btn-sm'],
			'Bootstrap 4'	=> ['4.6.2', 'btn btn-secondary btn-sm'],
			'Bootstrap 5'	=> ['5.3.3', 'btn btn-secondary btn-sm'],
		];
	}

	/**
	 *	@covers		::__construct
	 *	@covers		::render
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expectedClass ): void
	{
		$link	= ( new ButtonLink( '#x', 'Label', Button::SIZE_SMALL, 'star' ) )->setBsVersion( $version );
		self::assertSame( '<a class="'.$expectedClass.'" href="#x" role="button"><i class="fa fa-star"></i> Label</a>', $link->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::setConfirm
	 *	@covers		::setTitle
	 *	@return		void
	 */
	public function testRenderWithConfirmAndTitle(): void
	{
		$link	= ( new ButtonLink( '#delete', 'Delete', Button::STATE_DANGER ) )
			->setConfirm( "Really delete 'x'?" )
			->setTitle( 'Delete item' )
			->setId( 'button-delete' );
		$expected	= '<a id="button-delete" class="btn btn-danger" href="#delete" title="Delete item" role="button" '.
			'onclick="if(!confirm(&#039;Really delete \&#039;x\&#039;?&#039;))return false;">Delete</a>';
		self::assertSame( $expected, $link->render() );
	}

	/**
	 *	Disabled link keeps URL and click handler as data attributes, without being clickable.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderDisabled(): void
	{
		$link	= ( new ButtonLink( '#x', 'Label', NULL, NULL, TRUE ) )->setConfirm( 'Sure?' );
		$expected	= '<a class="btn disabled" role="button" data-attr-href="#x" data-attr-onclick="if(!confirm(&#039;Sure?&#039;))return false;">Label</a>';
		self::assertSame( $expected, $link->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderIconOnly(): void
	{
		$link	= new ButtonLink( '#x', '', NULL, 'star' );
		self::assertSame( '<a class="btn" href="#x" role="button"><i class="fa fa-star"></i></a>', $link->render(), 'No space without label' );
	}

	protected function setUp(): void
	{
		$this->defaultIconSet	= Icon::$defaultSet;
		Icon::$defaultSet		= 'fontawesome';
	}

	/**
	 *	Title is escaped for attribute only, without slashes shown in tooltip.
	 *	@covers		::render
	 *	@covers		::setTitle
	 *	@return		void
	 */
	public function testRenderTitleWithQuotes(): void
	{
		$link	= ( new ButtonLink( '#x', 'Label' ) )->setTitle( 'Peter\'s "Q"' );
		self::assertStringContainsString( 'title="Peter&#039;s &quot;Q&quot;"', $link->render() );
	}

	/**
	 *	Confirmation runs before click events, which must not replace it.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderConfirmWithClickEvent(): void
	{
		$link	= ( new ButtonLink( '#x', 'Label' ) )->setConfirm( 'Sure?' )->setEvent( 'click', 'go("a")' );
		$expected	= '<a class="btn btn-default" href="#x" role="button" onclick="if(!confirm(&#039;Sure?&#039;))return false; go(&quot;a&quot;)">Label</a>';
		self::assertSame( $expected, $link->setBsVersion( '3' )->render() );
	}

	/**
	 *	Disabled link keeps click events as data attribute, without running them.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderDisabledWithClickEvent(): void
	{
		$link	= ( new ButtonLink( '#x', 'Label', NULL, NULL, TRUE ) )->setEvent( 'click', 'go()' );
		$html	= $link->render();
		self::assertStringNotContainsString( ' onclick=', $html );
		self::assertStringContainsString( 'data-attr-onclick="go()"', $html );
	}

	protected function tearDown(): void
	{
		Icon::$defaultSet		= $this->defaultIconSet;
	}
}
