<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Code;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Code
 * @covers			\CeusMedia\Bootstrap\Code
 */
class CodeTest extends TestCase
{
	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$code	= new Code( "<?php\n\techo 'x';" );
		self::assertSame( "<pre>&lt;?php\n    echo &#039;x&#039;;</pre>", $code->render() );

		$code	= new Code( "\tx", FALSE, 'my-code', FALSE );
		self::assertSame( "<pre class=\"my-code\">\tx</pre>", $code->render() );
	}

	/**
	 *	Bootstrap 4 and 5 do not style preformatted text, so utility classes keep the look of older versions.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderLookSinceBootstrap4(): void
	{
		self::assertSame( '<pre>x</pre>', ( new Code( 'x' ) )->setBsVersion( '3' )->render() );
		self::assertSame( '<pre class="bg-light border rounded p-3">x</pre>', ( new Code( 'x' ) )->setBsVersion( '4' )->render() );
		self::assertSame( '<pre class="bg-light border rounded p-3">x</pre>', ( new Code( 'x' ) )->setBsVersion( '5' )->render() );
	}

	/**
	 *	@return		array<string,array{string,string}>
	 */
	public static function scrollableProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', '<pre class="my-code pre-scrollable">x</pre>'],
			'Bootstrap 3'	=> ['3.4.1', '<pre class="my-code pre-scrollable">x</pre>'],
			'Bootstrap 4'	=> ['4.6.2', '<pre class="bg-light border rounded p-3 my-code" style="max-height: 340px; overflow-y: scroll">x</pre>'],
			'Bootstrap 5'	=> ['5.3.3', '<pre class="bg-light border rounded p-3 my-code" style="max-height: 340px; overflow-y: scroll">x</pre>'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setScrollable
	 *	@dataProvider	scrollableProvider
	 *	@return		void
	 */
	public function testRenderScrollable( string $version, string $expected ): void
	{
		$code	= ( new Code( 'x', TRUE, 'my-code' ) )->setBsVersion( $version );
		self::assertSame( $expected, $code->render() );
	}
}
