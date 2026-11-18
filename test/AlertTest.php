<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Alert
 * @covers			\CeusMedia\Bootstrap\Alert
 * @covers			\CeusMedia\Bootstrap\Base\StateClassParser
 * @covers			\CeusMedia\Bootstrap\Base\AlertClassResolver
 */
class AlertTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$label	= 'Alert Message';
		$class	= Alert::CLASS_SUCCESS;
		$alert	= new UnprotectedAlert( $label, $class );

		self::assertEquals( $label, $alert->getContent() );
		self::assertEquals( explode( ' ', $class ), $alert->getClasses() );

		$class	= Alert::CLASS_ERROR;
		$alert	= new UnprotectedAlert( $label, $class );
		self::assertEquals( explode( ' ', $class ), $alert->getClasses() );
	}

	/**
	 *	@covers		::useDismiss
	 *	@return		void
	 */
	public function testUseDismiss(): void
	{
		$label	= 'Alert Message';
		$class	= Alert::CLASS_SUCCESS;
		$alert	= new UnprotectedAlert( $label, $class );
		$alert->useDismiss( TRUE );

		$expected	= '<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Alert Message</div>';
		self::assertEquals( $expected, $alert->render() );

		$alert->useDismiss( FALSE );

		$expected	= '<div class="alert alert-success" role="alert">Alert Message</div>';
		self::assertEquals( $expected, $alert->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'alert', 'alert alert-error', 'alert alert-info', 'alert alert-success'],
			'Bootstrap 3'	=> ['3.4.1', 'alert alert-warning', 'alert alert-danger', 'alert alert-info', 'alert alert-success'],
			'Bootstrap 4'	=> ['4.6.2', 'alert alert-warning', 'alert alert-danger', 'alert alert-dark', 'alert alert-success'],
			'Bootstrap 5'	=> ['5.3.3', 'alert alert-warning', 'alert alert-danger', 'alert alert-dark', 'alert alert-success'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::realizeStateClasses
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderStates( string $version, string $default, string $danger, string $dark, string $success ): void
	{
		$cases	= [
			$default	=> NULL,
			$danger		=> Alert::CLASS_ERROR,
			$dark		=> Alert::CLASS_DARK,
			$success	=> Alert::CLASS_SUCCESS,
		];
		foreach( $cases as $expected => $class ){
			$alert	= ( new Alert( 'X', $class ) )->setBsVersion( $version );
			self::assertSame( '<div class="'.$expected.'" role="alert">X</div>', $alert->render() );
		}
		$alert	= ( new Alert( 'X', Alert::CLASS_DANGER.' my-alert' ) )->setBsVersion( $version );
		self::assertSame( '<div class="'.$danger.' my-alert" role="alert">X</div>', $alert->render() );
	}

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function dismissProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>X</div>'],
			'Bootstrap 3'	=> ['3.4.1', '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>X</div>'],
			'Bootstrap 4'	=> ['4.6.2', '<div class="alert alert-success alert-dismissible fade show" role="alert">X<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'],
			'Bootstrap 5'	=> ['5.3.3', '<div class="alert alert-success alert-dismissible fade show" role="alert">X<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderDismissButton
	 *	@dataProvider	dismissProvider
	 *	@return		void
	 */
	public function testRenderDismissable( string $version, string $expected ): void
	{
		$alert	= ( new Alert( 'X', Alert::CLASS_SUCCESS ) )->setBsVersion( $version )->useDismiss();
		self::assertSame( $expected, $alert->render() );
	}
}

class UnprotectedAlert extends Alert
{
	public function getClasses(): array
	{
		return $this->classes;
	}

	public function getContent(): Renderable|Stringable|array|string|NULL
	{
		return $this->content;
	}
}
