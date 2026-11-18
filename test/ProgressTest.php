<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Progress;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Progress
 * @covers			\CeusMedia\Bootstrap\Progress
 * @covers			\CeusMedia\Bootstrap\Base\DataObject\ProgressBar
 */
class ProgressTest extends TestCase
{
	/**
	 *	@return		array<string,array{string,string,string}>
	 */
	public static function versionProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'bar bar-danger', 'bar bar-warning'],
			'Bootstrap 3'	=> ['3.4.1', 'progress-bar progress-bar-danger', 'progress-bar progress-bar-warning'],
			'Bootstrap 4'	=> ['4.6.2', 'progress-bar bg-danger', 'progress-bar bg-warning'],
			'Bootstrap 5'	=> ['5.3.3', 'progress-bar bg-danger', 'progress-bar bg-warning'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::realizeBarClasses
	 *	@dataProvider	versionProvider
	 *	@return		void
	 */
	public function testRenderColors( string $version, string $expectedDanger, string $expectedWarning ): void
	{
		$progress	= ( new Progress() )
			->setBsVersion( $version )
			->addBar( 50, Progress::BAR_CLASS_DANGER )
			->addBar( 25, Progress::BAR_CLASS_WARNING, 'Label' );

		$attributes	= 'role="progressbar" aria-valuemin="0" aria-valuemax="100"';
		$expected	= '<div class="progress">'.
			'<div class="'.$expectedDanger.'" style="width: 50%" '.$attributes.' aria-valuenow="50"></div>'.
			'<div class="'.$expectedWarning.'" style="width: 25%" '.$attributes.' aria-valuenow="25">Label</div>'.
			'</div>';
		self::assertSame( $expected, $progress->render() );
	}

	/**
	 *	@return		array<string,array{string,string,string}>
	 */
	public static function stripedProvider(): array
	{
		return [
			'Bootstrap 2'	=> ['2.3.2', 'progress progress-striped active', 'bar bar-success'],
			'Bootstrap 3'	=> ['3.4.1', 'progress', 'progress-bar progress-bar-success progress-bar-striped active'],
			'Bootstrap 4'	=> ['4.6.2', 'progress', 'progress-bar bg-success progress-bar-striped progress-bar-animated'],
			'Bootstrap 5'	=> ['5.3.3', 'progress', 'progress-bar bg-success progress-bar-striped progress-bar-animated'],
		];
	}

	/**
	 *	Container classes of Bootstrap 2 (color, striped, active) are translated for later versions.
	 *	@covers		::render
	 *	@covers		::realizeBarClasses
	 *	@dataProvider	stripedProvider
	 *	@return		void
	 */
	public function testRenderStripedAndAnimated( string $version, string $expectedContainer, string $expectedBar ): void
	{
		$progress	= ( new Progress( Progress::CLASS_SUCCESS.' '.Progress::CLASS_STRIPED.' '.Progress::CLASS_ACTIVE ) )
			->setBsVersion( $version )
			->addBar( 40 );
		$html		= $progress->render();
		self::assertStringStartsWith( '<div class="'.$expectedContainer.'"><div class="'.$expectedBar.'"', $html );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderKeepsUnknownClasses(): void
	{
		$progress	= ( new Progress( 'my-progress' ) )
			->setBsVersion( '3' )
			->addBar( 10, 'my-bar bg-info' );
		self::assertStringStartsWith( '<div class="progress my-progress"><div class="progress-bar progress-bar-info my-bar"', $progress->render() );
	}

	/**
	 *	Stripes and animation can be set per bar, with class names of any version.
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRenderStripedAndAnimatedBar(): void
	{
		$progress	= ( new Progress() )->setBsVersion( '5' )->addBar( 30, 'progress-bar-striped active' );
		self::assertStringStartsWith( '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 30%"', $progress->render() );

		$progress	= ( new Progress() )->setBsVersion( '2' )->addBar( 30, 'progress-bar-striped active' );
		self::assertStringStartsWith( '<div class="progress progress-striped active"><div class="bar"', $progress->render(), 'Bootstrap 2: on container' );
	}
}
