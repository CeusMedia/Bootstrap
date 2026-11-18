<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Button\Link as ButtonLink;
use CeusMedia\Bootstrap\Button\Submit;
use CeusMedia\Bootstrap\Card;
use CeusMedia\Bootstrap\Code;
use CeusMedia\Bootstrap\Heading;
use CeusMedia\Bootstrap\Label;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Modal\Dialog as ModalDialog;
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;
use CeusMedia\Bootstrap\Shiftbox;
use PHPUnit\Framework\TestCase;

/**
 *	Components must render data attributes, events and aria attributes set by setData(), setEvent() and setAria(),
 *	in all Bootstrap versions, since these methods are available on them.
 *	@coversNothing
 */
class AttributeRenderingTest extends TestCase
{
	/**
	 *	@return		array<string,array{Element}>
	 */
	public static function elementProvider(): array
	{
		return [
			'Alert'			=> [new Alert( 'x' )],
			'Badge'			=> [new Badge( 'x' )],
			'Button'		=> [new Button( 'x' )],
			'Button\Link'	=> [new ButtonLink( '#', 'x' )],
			'Button\Submit'	=> [new Submit( 'save', 'x' )],
			'Card'			=> [new Card( 'x', 'Header' )],
			'Code'			=> [new Code( 'x' )],
			'Heading'		=> [new Heading( 'x' )],
			'Label'			=> [new Label( 'x' )],
			'Link'			=> [new Link( '#', 'x' )],
			'Row'			=> [new Row( 'x' )],
			'RowSpan'		=> [new RowSpan( 'x' )],
		];
	}

	/**
	 *	@dataProvider	elementProvider
	 *	@return		void
	 */
	public function testRenderDataAndEvents( Element $component ): void
	{
		$component->setData( 'test-key', 'value' )->setEvent( 'click', 'go()' );
		foreach( ['2', '3', '4', '5'] as $version ){
			$html	= $component->setBsVersion( $version )->render();
			self::assertStringContainsString( 'data-test-key="value"', $html, 'Data, Bootstrap '.$version );
			self::assertStringContainsString( 'onclick="go()"', $html, 'Event, Bootstrap '.$version );
		}
	}

	/**
	 *	Shiftbox: events in all versions, data attributes (options of plugin bootstrap-switch) up to Bootstrap 3.
	 *	@return		void
	 */
	public function testRenderShiftbox(): void
	{
		$component	= ( new Shiftbox( 'power', 'on', FALSE, ['size' => 'small'] ) )->setEvent( 'change', 'go()' );
		foreach( ['2', '3', '4', '5'] as $version ){
			$html	= $component->setBsVersion( $version )->render();
			self::assertStringContainsString( 'onchange="go()"', $html, 'Event, Bootstrap '.$version );
			$hasData	= str_contains( $html, 'data-size="small"' );
			self::assertSame( in_array( $version, ['2', '3'], TRUE ), $hasData, 'Data, Bootstrap '.$version );
		}
	}

	/**
	 *	@return		void
	 */
	public function testRenderAria(): void
	{
		$components	= [
			'Row'			=> new Row( 'x' ),
			'RowSpan'		=> new RowSpan( 'x' ),
			'Modal\Dialog'	=> ( new ModalDialog( 'modal-test' ) )->setBody( 'x' ),
		];
		foreach( $components as $name => $component ){
			$component->setAria( 'live', 'polite' );
			foreach( ['2', '3', '4', '5'] as $version ){
				$html	= $component->setBsVersion( $version )->render();
				self::assertStringContainsString( 'aria-live="polite"', $html, $name.', Bootstrap '.$version );
			}
		}
	}

	/**
	 *	@return		void
	 */
	public function testRenderModalData(): void
	{
		$component	= ( new ModalDialog( 'modal-test' ) )->setBody( 'x' )->setData( 'test-key', 'value' );
		foreach( ['2', '3', '4', '5'] as $version ){
			$html	= $component->setBsVersion( $version )->render();
			self::assertStringStartsWith( '<div id="modal-test"', $html, 'Bootstrap '.$version );
			self::assertStringContainsString( 'data-test-key="value"', $html, 'Bootstrap '.$version );
		}
	}
}
