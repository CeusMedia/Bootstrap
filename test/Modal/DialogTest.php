<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Modal;

use CeusMedia\Bootstrap\Modal\Dialog;
use CeusMedia\Common\FS\File\Reader;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Modal\Dialog
 * @covers			\CeusMedia\Bootstrap\Modal\Dialog
 */
class DialogTest extends TestCase
{
	protected string $basePath;

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'dialogTestCompleteV2.html'],
			'Bootstrap 3'	=> ['3.4.1', 'dialogTestCompleteV3.html'],
			'Bootstrap 4'	=> ['4.6.2', 'dialogTestCompleteV4.html'],
			'Bootstrap 5'	=> ['5.3.3', 'dialogTestCompleteV5.html'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::renderHeader
	 *	@covers		::renderFooter
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testComplete( string $version, string $fileName ): void
	{
		$dialog	= $this->createCompleteDialog()->setBsVersion( $version );
		self::assertSame( $this->loadExpectation( $fileName ), (string) $dialog );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testWithoutHeaderAndFooter(): void
	{
		$dialog	= $this->createCompleteDialog()
			->setBsVersion( '2.3.2' )
			->useHeader( FALSE )
			->useFooter( FALSE );
		self::assertSame( $this->loadExpectation( 'dialogTestCompleteV2Monolog.html' ), (string) $dialog );
	}

	protected function createCompleteDialog(): Dialog
	{
		return Dialog::create()
			->setId( 'test:complete' )
			->setDialogClass( 'complete-test' )
			->setFade( TRUE )
			->setCentered( FALSE )
			->setHeading( 'Complete Modal Dialog Test' )
			->setFormAction( '/path/to/post/receiver' )
			->setSubmitButtonLabel( 'yes' )
			->setSubmitButtonClass( 'btn btn-primary' )
			->setSubmitButtonIconClass( 'icon-save' )
			->setCloseButtonLabel( 'no' )
			->setCloseButtonClass( 'btn btn-default' )
			->setCloseButtonIconClass( 'icon-close' )
			->setFormIsUpload( TRUE )
			->setFormSubmit( 'alert("This is just a test")' )
			->setBody( 'This is the dialog body.' );
	}

	protected function loadExpectation( string $fileName ): string
	{
		/** @var string $html */
		$html	= Reader::load( $this->basePath.$fileName );
		return (string) preg_replace( '/\r?\n */s', '', str_replace( '"post"', '"POST"', $html ) );
	}

	protected function setUp(): void
	{
		$this->basePath	= __DIR__ . '/';
	}

	/**
	 *	@covers		::__construct
	 *	@covers		::setAttributes
	 *	@covers		::setHeaderCloseButtonIcon
	 *	@covers		::render
	 *	@return		void
	 */
	public function testAttributes(): void
	{
		$dialog	= ( new Dialog( 'modal-x' ) )
			->setBsVersion( '2' )
			->useFooter( FALSE )
			->setHeaderCloseButtonIcon( 'x' )
			->setAttributes( [
				'id'				=> 'ignored',
				'role'				=> 'ignored',
				'tabindex'			=> 'ignored',
				'aria-hidden'		=> 'ignored',
				'class'				=> 'my-modal',
				'data-backdrop'		=> 'static',
			] );
		$html	= $dialog->render();
		self::assertStringStartsWith( '<div id="modal-x" class="modal hide my-modal modal-md" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-x-label" data-backdrop="static">', $html );
		self::assertStringContainsString( 'aria-hidden="true">x</button>', $html );
	}
}
