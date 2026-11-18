<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest\Modal;

use CeusMedia\Bootstrap\Modal\Dialog;
use CeusMedia\Common\FS\File\Reader;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Modal\Dialog
 */
class DialogTest extends TestCase
{
	protected string $basePath;

	public function testComplete(): void
	{
		$d	= Dialog::create()
//			->setBsVersion( '4' )
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
			->setBody( 'This is the dialog body.' )
//			->useHeader( FALSE )
//			->useFooter( FALSE )
		;
/*		file_put_contents( $this->basePath.'dialogTestCompleteV2Monolog.html', \tidy::repairString( $d->render(), [
			'indent'			=> TRUE,
			'wrap'				=> 0,
			'show-body-only'	=> TRUE,
			'force-output'		=> TRUE,
			'quiet'				=> FALSE,
			'drop-empty-elements' => FALSE,
		] ) );*/

		/** @var string $e */
		$e	= Reader::load( $this->basePath.'dialogTestCompleteV2.html' );
		/** @var string $e */
		$e	= preg_replace( '/\r?\n */s', '', str_replace( '"post"', '"POST"', $e ) );
		self::assertSame( $e, (string) $d );

		/** @var string $e */
		$e	= Reader::load( $this->basePath.'dialogTestCompleteV4.html' );
		$e	= preg_replace( '/\r?\n */s', '', str_replace( '"post"', '"POST"', $e ) );
		self::assertSame( $e, (string) $d->setBsVersion( '4' ) );

		/** @var string $e */
		$e	= Reader::load( $this->basePath.'dialogTestCompleteV2Monolog.html' );
		$e	= preg_replace( '/\r?\n */s', '', str_replace( '"post"', '"POST"', $e ) );
		$d->setBsVersion( '2.3.2' )->useHeader( FALSE )->useFooter( FALSE );
		self::assertSame( $e, (string) $d );
	}

	protected function setUp(): void
	{
		$this->basePath	= __DIR__ . '/';
	}
}