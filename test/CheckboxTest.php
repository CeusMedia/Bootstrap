<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Checkbox;
use CeusMedia\Bootstrap\Icon;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Checkbox
 * @covers			\CeusMedia\Bootstrap\Checkbox
 */
class CheckboxTest extends TestCase
{
	protected string $defaultIconSet;

	/**
	 *	@return		array<string,array{string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2'],
			'Bootstrap 3'	=> ['3.4.1'],
			'Bootstrap 4'	=> ['4.6.2'],
			'Bootstrap 5'	=> ['5.3.3'],
		];
	}

	/**
	 *	Markup is the same in all versions, since Bootstrap has no checkboxes with icons.
	 *	@covers		::render
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version ): void
	{
		$checkbox	= ( new Checkbox( 'agree', 1, TRUE, 'I agree' ) )->setBsVersion( $version );
		$expected	= '<div class="checkbox cr-checkbox"><label>'.
			'<input type="checkbox" name="agree" id="input_agree" value="1" checked="checked"/>'.
			'<span class="cr"><span class="cr-icon"><i class="fa fa-check"></i></span></span>'.
			'I agree</label></div>';
		self::assertSame( $expected, $checkbox->render() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderWithIcon(): void
	{
		$checkbox	= new Checkbox( 'c', 1, FALSE, 'Label', 'fa fa-star' );
		self::assertStringContainsString( '<span class="cr-icon"><i class="fa fa-star"></i></span>', $checkbox->render(), 'Complete classes are kept' );

		Icon::$defaultSet	= 'fontawesome6';
		$checkbox	= new Checkbox( 'c', 1, FALSE, 'Label', 'star' );
		self::assertStringContainsString( '<span class="cr-icon"><i class="fa fa-star"></i></span>', $checkbox->render(), 'Short name is resolved for icon set' );

		$checkbox	= new Checkbox( 'c', 1, FALSE, 'Label', new Icon( 'heart', 'regular' ) );
		self::assertStringContainsString( '<span class="cr-icon"><i class="fa-regular fa-heart"></i></span>', $checkbox->render(), 'Icon object' );
	}

	protected function setUp(): void
	{
		$this->defaultIconSet	= Icon::$defaultSet;
	}

	protected function tearDown(): void
	{
		Icon::$defaultSet	= $this->defaultIconSet;
	}

	/**
	 *	@covers		::__construct
	 *	@covers		::setValue
	 *	@covers		::render
	 *	@return		void
	 */
	public function testValueLabelAndData(): void
	{
		$checkbox	= new Checkbox( 'c', 'a<b', FALSE, new \CeusMedia\Bootstrap\Badge( 'new' ), 'fa fa-check', ['topic' => 'x'] );
		$html		= $checkbox->render();
		self::assertStringContainsString( 'value="a&lt;b"', $html );
		self::assertStringContainsString( 'data-topic="x"', $html );
		self::assertStringContainsString( '<span class="badge">new</span></label>', $html, 'Label may be a component' );
	}
}
