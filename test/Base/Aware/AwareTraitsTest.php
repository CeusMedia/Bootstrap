<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base\Aware;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Link;
use DomainException;
use PHPUnit\Framework\TestCase;
use RangeException;

/**
 *	Tests traits, which add attributes and properties to components, by components using them.
 * @covers			\CeusMedia\Bootstrap\Base\Aware\AriaAware
 * @covers			\CeusMedia\Bootstrap\Base\Aware\ClassAware
 * @covers			\CeusMedia\Bootstrap\Base\Aware\ContentAware
 * @covers			\CeusMedia\Bootstrap\Base\Aware\DataAware
 * @covers			\CeusMedia\Bootstrap\Base\Aware\EventAware
 * @covers			\CeusMedia\Bootstrap\Base\Aware\NameAware
 * @covers			\CeusMedia\Bootstrap\Base\Aware\SizeAware
 */
class AwareTraitsTest extends TestCase
{
	public function testAriaAndRole(): void
	{
		$link	= ( new Link( '#', 'x' ) )
			->setAria( 'Expanded', TRUE )
			->setAria( 'hidden', FALSE )
			->setAria( 'label', 'A "label"' )
			->setRole( 'Button' );
		self::assertSame( '<a href="#" aria-expanded="true" aria-hidden="false" aria-label="A &quot;label&quot;" role="button">x</a>', $link->render() );
	}

	public function testClasses(): void
	{
		$badge	= new Badge( 'x', ['a', ' b ', ''] );
		$badge->addClass( 'c  d a' )->removeClass( 'b' )->removeClass( 'unknown' )->removeClass( ' ' );
		self::assertSame( '<span class="badge a c d">x</span>', $badge->render() );

		$badge->setClass( 'e' );
		self::assertSame( '<span class="badge e">x</span>', $badge->render() );
		$badge->setClass();
		self::assertSame( '<span class="badge">x</span>', $badge->render() );
	}

	public function testContent(): void
	{
		$badge	= new Badge( 'x' );
		self::assertSame( 'x', $badge->getContent() );
		self::assertSame( '', $badge->setContent( NULL )->getContentAsString() );
		self::assertSame( 'ab1', $badge->setContent( ['a', NULL, 'b', 1] )->getContentAsString(), 'NULL items are skipped' );
	}

	public function testData(): void
	{
		$link	= ( new Link( '#', 'x' ) )
			->setData( 'topicId', 5 )
			->setData( 'title', 'A "b"' );
		self::assertSame( '<a href="#" data-topic-id="5" data-title="A &quot;b&quot;">x</a>', $link->render(), 'Camel case keys are converted' );

		$link->setData( 'title', 'c', FALSE );
		self::assertStringContainsString( 'data-title="c"', $link->render(), 'Not strict: value is replaced' );

		$this->expectException( DomainException::class );
		$link->setData( 'title', 'd' );
	}

	public function testEvents(): void
	{
		$link	= ( new Link( '#', 'x' ) )
			->setEvent( ' Click ', 'a()' )
			->setEvent( 'click', 'b()' )
			->setEvent( 'mouseover', "c('x')" );
		self::assertSame( '<a href="#" onclick="a(); b()" onmouseover="c(&#039;x&#039;)">x</a>', $link->render() );
	}

	public function testName(): void
	{
		self::assertNull( ( new Button( 'x' ) )->getName() );
		$button	= ( new Button( 'x' ) )->setName( 'save' );
		self::assertSame( 'save', $button->getName() );
		self::assertStringNotContainsString( 'id=', $button->render(), 'Buttons do not get ID from name' );
	}

	public function testSize(): void
	{
		$button	= new Button( 'x' );
		self::assertSame( 'SIZE_DEFAULT', $button->getSize() );
		$button->setSize( Button::SIZE_LARGE );
		self::assertSame( 'SIZE_LARGE', $button->getSize() );

		$this->expectException( RangeException::class );
		$button->setSize( 'btn-huge' );
	}
}
