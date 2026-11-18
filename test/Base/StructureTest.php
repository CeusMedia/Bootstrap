<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Base\Structure;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\Structure
 * @covers			\CeusMedia\Bootstrap\Base\Structure
 */
class StructureTest extends TestCase
{
	/**
	 *	@covers		::__toString
	 *	@return		void
	 */
	public function testToString(): void
	{
		self::assertEquals( '<div>ok</div>', (string) new WorkingStructure() );
	}

	/**
	 *	@covers		::__toString
	 *	@return		void
	 */
	public function testToStringTriggersWarningOnFailure(): void
	{
		$errors	= [];
		set_error_handler( static function( int $level, string $message ) use ( &$errors ): bool {
			$errors[]	= [$level, $message];
			return TRUE;
		} );
		try{
			$result	= (string) new FailingStructure();
		}
		finally{
			restore_error_handler();
		}

		self::assertEquals( '', $result );
		self::assertCount( 1, $errors );
		self::assertEquals( E_USER_WARNING, $errors[0][0] );
		self::assertStringContainsString( FailingStructure::class, $errors[0][1] );
		self::assertStringContainsString( 'render failed', $errors[0][1] );
	}
}

class WorkingStructure extends Structure
{
	public function render(): string
	{
		return '<div>ok</div>';
	}
}

class FailingStructure extends Structure
{
	public function render(): string
	{
		throw new RuntimeException( 'render failed' );
	}
}
