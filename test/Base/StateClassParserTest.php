<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Base\StateClassParser;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\StateClassParser
 * @covers			\CeusMedia\Bootstrap\Base\StateClassParser
 */
class StateClassParserTest extends TestCase
{
	/**
	 *	@return		array<string,array{list<string>,list<string>,string|NULL,list<string>}>
	 */
	public static function classProvider(): array
	{
		return [
			'none'					=> [[],								['alert'],				NULL,			[]],
			'error (2)'				=> [['alert alert-error'],			['alert'],				'danger',		['alert']],
			'important (2)'			=> [['label-important'],			['label'],				'danger',		[]],
			'inverse (2)'			=> [['badge-inverse'],				['badge'],				'dark',			[]],
			'default (3)'			=> [['label-default'],				['label'],				'secondary',	[]],
			'background (5)'		=> [['bg-warning text-dark'],		['badge', 'bg'],		'warning',		['text-dark']],
			'other prefix'			=> [['alert-danger'],				['badge'],				NULL,			['alert-danger']],
			'unknown state'			=> [['alert-dismissible'],			['alert'],				NULL,			['alert-dismissible']],
			'last state wins'		=> [['alert-info', 'alert-success'],['alert'],				'success',		[]],
			'others unique'			=> [['x y', 'x'],					['alert'],				NULL,			['x', 'y']],
		];
	}

	/**
	 *	@covers		::__construct
	 *	@covers		::normalize
	 *	@dataProvider	classProvider
	 *	@param		list<string>	$classes
	 *	@param		list<string>	$prefixes
	 *	@param		list<string>	$expectedOthers
	 *	@return		void
	 */
	public function testParse( array $classes, array $prefixes, ?string $expectedState, array $expectedOthers ): void
	{
		$parser	= new StateClassParser( $classes, $prefixes );
		self::assertSame( $expectedState, $parser->state );
		self::assertSame( $expectedOthers, $parser->others );
	}
}
