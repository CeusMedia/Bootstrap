<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Shiftbox;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Shiftbox
 * @covers			\CeusMedia\Bootstrap\Shiftbox
 */
class ShiftboxTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2',
				'<input type="checkbox" id="input_power" name="power" value="on" class="shiftbox" checked="checked" data-size="small" data-on-text="I"/>',
				'<label class="checkbox"><input type="checkbox" id="input_power" name="power" value="on" class="shiftbox" checked="checked" data-size="small" data-on-text="I"/>&nbsp;Power</label>'],
			'Bootstrap 3'	=> ['3.4.1',
				'<input type="checkbox" id="input_power" name="power" value="on" class="shiftbox" checked="checked" data-size="small" data-on-text="I"/>',
				'<div class="checkbox"><label><input type="checkbox" id="input_power" name="power" value="on" class="shiftbox" checked="checked" data-size="small" data-on-text="I"/>&nbsp;Power</label></div>'],
			'Bootstrap 4'	=> ['4.6.2',
				'<div class="custom-control custom-switch"><input type="checkbox" id="input_power" name="power" value="on" class="custom-control-input" checked="checked"/><label class="custom-control-label" for="input_power"></label></div>',
				'<div class="custom-control custom-switch"><input type="checkbox" id="input_power" name="power" value="on" class="custom-control-input" checked="checked"/><label class="custom-control-label" for="input_power">Power</label></div>'],
			'Bootstrap 5'	=> ['5.3.3',
				'<div class="form-check form-switch"><input type="checkbox" id="input_power" name="power" value="on" class="form-check-input" checked="checked" role="switch"/></div>',
				'<div class="form-check form-switch"><input type="checkbox" id="input_power" name="power" value="on" class="form-check-input" checked="checked" role="switch"/><label class="form-check-label" for="input_power">Power</label></div>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setLabel
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRender( string $version, string $expected, string $expectedWithLabel ): void
	{
		$shiftbox	= ( new Shiftbox( 'power', 'on', TRUE, ['size' => 'small', 'on-text' => 'I'] ) )->setBsVersion( $version );
		self::assertSame( $expected, $shiftbox->render() );
		self::assertSame( $expectedWithLabel, $shiftbox->setLabel( 'Power' )->render() );
	}
}
