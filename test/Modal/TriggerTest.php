<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Modal;

use CeusMedia\Bootstrap\Modal\Trigger;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Modal\Trigger
 * @covers			\CeusMedia\Bootstrap\Modal\Trigger
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

	public function testRenderForBootstrap5(): void
	{
		$t	= Trigger::create()
			->setBsVersion( '5.3.3' )
			->setLabel( 'open' )
			->setModalId( 'modal:test' )
			->asButton();

		$e	= '<button href="#modal:test" role="button" class="btn btn-secondary" data-bs-toggle="modal" type="button">open</button>';
		self::assertSame( $e, (string) $t );
	}

	protected function setUp(): void
	{
		$this->basePath	= __DIR__ . '/';
	}

	public function testConstructAndAttributes(): void
	{
		$t	= new Trigger( 'modal:x', 'open', 'btn-info', 'icon-test' );
		$t->setAttributes( [
			'id'			=> 'ignored',
			'href'			=> 'ignored',
			'role'			=> 'ignored',
			'data-toggle'	=> 'ignored',
			'data-bs-toggle'=> 'ignored',
			'title'			=> 'Open dialog',
		] );
		$e	= '<button href="#modal:x" role="button" class="btn btn-info" data-toggle="modal" title="Open dialog" type="button"><i class="icon-icon-test"></i>&nbsp;open</button>';
		self::assertSame( $e, $t->render() );
	}

	public function testRenderWithoutLabelThrowsException(): void
	{
		$this->expectException( \RuntimeException::class );
		Trigger::create()->setModalId( 'x' )->render();
	}

	public function testRenderWithoutModalIdThrowsException(): void
	{
		$this->expectException( \RuntimeException::class );
		Trigger::create()->setLabel( 'x' )->render();
	}
}