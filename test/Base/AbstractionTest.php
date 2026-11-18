<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Base;

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Base\Abstraction;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Dropdown\Menu;
use DomainException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Base\Abstraction
 * @covers			\CeusMedia\Bootstrap\Base\Abstraction
 */
class AbstractionTest extends TestCase
{
	protected string $defaultBsVersion;

	/**
	 *	@covers		::setDefaultBsVersion
	 *	@covers		::getDefaultBsVersion
	 *	@return		void
	 */
	public function testSetDefaultBsVersion(): void
	{
		Abstraction::setDefaultBsVersion( '4.6.2' );
		self::assertSame( BsVersion::V4, Abstraction::getDefaultBsVersion() );
		self::assertSame( BsVersion::V4, ( new Badge( 'x' ) )->getBsVersion(), 'New components use default' );

		Abstraction::setDefaultBsVersion( BsVersion::V5 );
		self::assertSame( BsVersion::V5, Abstraction::getDefaultBsVersion() );
	}

	/**
	 *	@covers		::setDefaultBsVersion
	 *	@return		void
	 */
	public function testSetDefaultBsVersionThrowsOnUnsupportedVersion(): void
	{
		try{
			Abstraction::setDefaultBsVersion( '6.0.0' );
			self::fail( 'Exception expected' );
		}
		catch( DomainException ){
			self::assertSame( '2.3.2', Abstraction::$defaultBsVersion, 'Default must not change' );
		}
	}

	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstructThrowsOnUnsupportedDefaultSetDirectly(): void
	{
		Abstraction::$defaultBsVersion	= '6';
		$this->expectException( DomainException::class );
		new Badge( 'x' );
	}

	/**
	 *	String conversion must not end the application, but trigger a warning and return an empty string.
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
			$results	= [
				(string) new FailingElement( 'x' ),
				(string) new FailingMenu(),
			];
		}
		finally{
			restore_error_handler();
		}
		self::assertSame( ['', ''], $results );
		self::assertCount( 2, $errors );
		self::assertSame( E_USER_WARNING, $errors[0][0] );
		self::assertStringContainsString( FailingElement::class.' failed: element failed', $errors[0][1] );
		self::assertStringContainsString( FailingMenu::class.' failed: menu failed', $errors[1][1] );
	}

	protected function setUp(): void
	{
		$this->defaultBsVersion	= Abstraction::$defaultBsVersion;
	}

	protected function tearDown(): void
	{
		Abstraction::$defaultBsVersion	= $this->defaultBsVersion;
	}

	/**
	 *	@covers		::getVersion
	 *	@covers		::supportsVersion
	 *	@return		void
	 */
	public function testLibraryVersion(): void
	{
		self::assertSame( Abstraction::$version, Abstraction::getVersion() );
		self::assertTrue( Abstraction::supportsVersion( '0.7.0', '0.6.0' ) );
		self::assertFalse( Abstraction::supportsVersion( '0.6.0', '0.7.0' ) );
		self::assertTrue( Abstraction::supportsVersion( '99.0' ) );
	}

	/**
	 *	@covers		::create
	 *	@return		void
	 */
	public function testCreate(): void
	{
		$badge	= Badge::create( '1', Badge::CLASS_INFO );
		self::assertSame( '<span class="badge badge-info">1</span>', $badge->render() );

		$menu	= Menu::create()->add( '#', 'A' );
		self::assertInstanceOf( Menu::class, $menu );
	}

	/**
	 *	@covers		::dialect
	 *	@covers		::realizeRenderableOrStringableProperty
	 *	@return		void
	 */
	public function testProtectedHelpers(): void
	{
		$component	= new ExposingComponent();
		self::assertSame( 'data-bs-toggle', $component->setBsVersion( '5' )->exposeDialect()->data( 'toggle' ) );

		self::assertSame( 'text', $component->setValue( 'text' )->exposeProperty( 'value' ) );
		self::assertSame( '<span class="badge">1</span>', $component->setValue( new Badge( '1' ) )->exposeProperty( 'value' ), 'Renderable' );
		self::assertSame( 'stringable', $component->setValue( new class implements \Stringable {
			public function __toString(): string { return 'stringable'; }
		} )->exposeProperty( 'value' ), 'Stringable' );
		self::assertSame( '', $component->setValue( NULL )->exposeProperty( 'value' ) );

		$this->expectException( DomainException::class );
		$component->exposeProperty( 'unknown' );
	}
}

class FailingElement extends Badge
{
	public function render(): string
	{
		throw new RuntimeException( 'element failed' );
	}
}

class FailingMenu extends Menu
{
	public function render(): string
	{
		throw new RuntimeException( 'menu failed' );
	}
}

class ExposingComponent extends Abstraction
{
	protected mixed $value	= NULL;

	public function render(): string
	{
		return '';
	}

	public function exposeDialect(): \CeusMedia\Bootstrap\Base\Dialect\Dialect
	{
		return $this->dialect();
	}

	public function exposeProperty( string $name ): ?string
	{
		return $this->realizeRenderableOrStringableProperty( $name );
	}

	public function setValue( mixed $value ): static
	{
		$this->value	= $value;
		return $this;
	}
}
