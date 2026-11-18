<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Dropdown;

use CeusMedia\Bootstrap\Dropdown\Trigger;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Dropdown\Trigger
 * @covers			\CeusMedia\Bootstrap\Dropdown\Trigger
 * @covers			\CeusMedia\Bootstrap\Dropdown\Trigger\Button
 * @covers			\CeusMedia\Bootstrap\Dropdown\Trigger\Link
 */
class TriggerTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2',
				'<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Drop <span class="caret"></span></button>',
				'<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Drop <span class="caret"></span></a>'],
			'Bootstrap 3'	=> ['3.4.1',
				'<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Drop <span class="caret"></span></button>',
				'<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Drop <span class="caret"></span></a>'],
			'Bootstrap 4'	=> ['4.6.2',
				'<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Drop</button>',
				'<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">Drop</a>'],
			'Bootstrap 5'	=> ['5.3.3',
				'<button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">Drop</button>',
				'<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">Drop</a>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::asButton
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderAsButton( string $version, string $expectedButton, string $expectedLink ): void
	{
		$trigger	= ( new Trigger( 'Drop', 'btn-info' ) )->setBsVersion( $version )->asButton();
		self::assertSame( $expectedButton, $trigger->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::asLink
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderAsLink( string $version, string $expectedButton, string $expectedLink ): void
	{
		$trigger	= ( new Trigger( 'Drop', 'nav-link' ) )->setBsVersion( $version )->asLink();
		self::assertSame( $expectedLink, $trigger->render() );
	}

	/**
	 *	Since Bootstrap 4, class "dropdown-toggle" draws the caret, so it is omitted without caret.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderWithoutCaretSinceBootstrap4(): void
	{
		$button	= ( new Trigger( 'Drop', 'btn-info', NULL, FALSE ) )->setBsVersion( '5' );
		self::assertSame( '<button type="button" class="btn btn-info" data-bs-toggle="dropdown">Drop</button>', $button->render() );

		$link	= ( new Trigger( 'Drop', 'nav-link', NULL, FALSE ) )->asLink()->setBsVersion( '4' );
		self::assertSame( '<a href="#" class="nav-link" data-toggle="dropdown">Drop</a>', $link->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderWithIconAndWithoutCaret(): void
	{
		$button	= ( new Trigger( 'Drop', 'btn-info', 'icon-star', FALSE ) )->setBsVersion( '2' );
		self::assertSame( '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-icon-star"></i> Drop</button>', $button->render() );

		$link	= ( new Trigger( 'Drop', 'nav-link', 'icon-star', FALSE ) )->asLink()->setBsVersion( '2' );
		self::assertSame( '<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><i class="icon-icon-star"></i> Drop</a>', $link->render() );
	}
}
