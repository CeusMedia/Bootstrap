<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Code;
use CeusMedia\Bootstrap\Label;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;
use PHPUnit\Framework\TestCase;

/**
 *	Components must render an ID set by setId(), in all Bootstrap versions,
 *	and must not render an ID attribute without ID.
 *	@coversNothing
 */
class IdRenderingTest extends TestCase
{
	/**
	 *	@return		array<string,array{Element,non-empty-string}>
	 */
	public static function componentProvider(): array
	{
		return [
			'Alert'		=> [new Alert( 'x' ), '<div id="my-id" class="alert'],
			'Badge'		=> [new Badge( 'x' ), '<span id="my-id" class="badge'],
			'Code'		=> [new Code( 'x' ), '<pre id="my-id"'],
			'Label'		=> [new Label( 'x' ), '<span id="my-id" class="'],
			'Link'		=> [new Link( '#', 'x' ), '<a id="my-id" href="#"'],
			'Row'		=> [new Row( 'x' ), '<div id="my-id" class="row'],
			'RowSpan'	=> [new RowSpan( 'x' ), '<div id="my-id" class="'],
		];
	}

	/**
	 *	@dataProvider	componentProvider
	 *	@param		non-empty-string	$expectedStart
	 *	@return		void
	 */
	public function testRenderId( Element $component, string $expectedStart ): void
	{
		foreach( ['2', '3', '4', '5'] as $version ){
			$component->setBsVersion( $version )->setId( NULL );
			self::assertStringNotContainsString( 'id=', $component->render(), 'Without ID, Bootstrap '.$version );
			$component->setId( 'my-id' );
			self::assertStringStartsWith( $expectedStart, $component->render(), 'Bootstrap '.$version );
		}
	}
}
