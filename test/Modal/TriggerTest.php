<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Modal;

use CeusMedia\Bootstrap\Modal\Trigger;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Modal\Trigger
 */
class TriggerTest extends TestCase
{
	protected string $basePath;

	public function testComplete(): void
	{
		$t	= Trigger::create()
			->setId( 'modal:trigger:test:complete' )
			->setLabel( 'complete-test' )
			->setIcon( 'icon-test' )
			->setModalId( 'modal:test:complete' )
			->setClass( 'test-class' )
			->asButton();

		$e	= '<button id="modal:trigger:test:complete" href="#modal:test:complete" role="button" class="btn test-class" data-toggle="modal" type="button"><i class="icon-icon-test"></i>&nbsp;complete-test</button>';
		self::assertSame( $e, (string) $t );
	}

	public function testAsLink(): void
	{
		$t	= Trigger::create()
			->setId( 'modal:trigger:test:complete' )
			->setLabel( 'complete-test' )
			->setIcon( 'icon-test' )
			->setModalId( 'modal:test:complete' )
			->setClass( 'test-class' )
			->asLink();

		$e	= '<a id="modal:trigger:test:complete" href="#modal:test:complete" role="button" class="btn test-class" data-toggle="modal"><i class="icon-icon-test"></i>&nbsp;complete-test</a>';
		self::assertSame( $e, (string) $t );
	}

	protected function setUp(): void
	{
		$this->basePath	= __DIR__ . '/';
	}
}