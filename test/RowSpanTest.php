<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\RowSpan;
use PHPUnit\Framework\TestCase;
use RangeException;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\RowSpan
 * @covers			\CeusMedia\Bootstrap\RowSpan
 */
class RowSpanTest extends TestCase
{
	protected string $defaultBreakpoint;

	/**
	 *	@return		array<string,array{array<string,int>,string,string,string,string}>
	 */
	public static function sizesProvider(): array
	{
		return [
			'none'						=> [[],									'span12',	'col-md-12',			'col-md-12',			'col-md-12'],
			'default breakpoint'		=> [['' => 4],							'span4',	'col-md-4',				'col-md-4',				'col-md-4'],
			'extra small'				=> [['xs' => 6],						'span6',	'col-xs-6',				'col-6',				'col-6'],
			'responsive'				=> [['xs' => 12, 'md' => 6, 'lg' => 4],	'span6',	'col-xs-12 col-md-6 col-lg-4',	'col-12 col-md-6 col-lg-4',	'col-12 col-md-6 col-lg-4'],
			'without md for V2'			=> [['lg' => 3, 'xs' => 12],			'span3',	'col-xs-12 col-lg-3',	'col-12 col-lg-3',		'col-12 col-lg-3'],
			'xl and xxl fall back'		=> [['md' => 6, 'xl' => 4, 'xxl' => 3],	'span6',	'col-md-6 col-lg-4',	'col-md-6 col-xl-4',	'col-md-6 col-xl-4 col-xxl-3'],
			'exact wins over fallback'	=> [['lg' => 6, 'xxl' => 3],			'span6',	'col-lg-6',				'col-lg-6 col-xl-3',	'col-lg-6 col-xxl-3'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::setSize
	 *	@covers		::getSizeClosestToMedium
	 *	@covers		::realizeColumnClasses
	 *	@dataProvider	sizesProvider
	 *	@param		array<string,int>	$sizes		Map of breakpoint (empty for default) to size
	 *	@return		void
	 */
	public function testRender( array $sizes, string $expectedV2, string $expectedV3, string $expectedV4, string $expectedV5 ): void
	{
		$span	= new RowSpan( 'X', 'my-span' );
		foreach( $sizes as $breakpoint => $size )
			$span->setSize( $size, '' !== $breakpoint ? $breakpoint : NULL );
		$expected	= ['2' => $expectedV2, '3' => $expectedV3, '4' => $expectedV4, '5' => $expectedV5];
		foreach( $expected as $version => $classes )
			self::assertSame( '<div class="'.$classes.' my-span">X</div>', $span->setBsVersion( (string) $version )->render(), 'Bootstrap '.$version );
	}

	/**
	 *	@covers		::setSize
	 *	@covers		::getSizes
	 *	@return		void
	 */
	public function testSetSize(): void
	{
		$span	= ( new RowSpan( 'X' ) )->setSize( 4, 'lg' )->setSize( 12, 'xs' )->setSize( 6 );
		self::assertSame( ['xs' => 12, 'md' => 6, 'lg' => 4], $span->getSizes(), 'Ordered by breakpoint' );

		$span->setSize( 8 );
		self::assertSame( ['xs' => 12, 'md' => 8, 'lg' => 4], $span->getSizes(), 'Same breakpoint replaces size' );
	}

	/**
	 *	@covers		::setSizes
	 *	@covers		::render
	 *	@return		void
	 */
	public function testSetSizes(): void
	{
		$span	= ( new RowSpan( 'X' ) )->setSize( 3, 'sm' )->setSizes( ['xl' => 4, 'xs' => 12, 'md' => 6] );
		self::assertSame( ['xs' => 12, 'md' => 6, 'xl' => 4], $span->getSizes(), 'Replaced and ordered by breakpoint' );
		self::assertSame( '<div class="col-12 col-md-6 col-xl-4">X</div>', $span->setBsVersion( '5' )->render() );

		self::assertSame( [], $span->setSizes( [] )->getSizes() );
	}

	/**
	 *	@covers		::setSizes
	 *	@return		void
	 */
	public function testSetSizesKeepsStateOnInvalidInput(): void
	{
		$span	= ( new RowSpan( 'X' ) )->setSizes( ['md' => 6] );
		try{
			$span->setSizes( ['xs' => 12, 'huge' => 4] );
			self::fail( 'Exception expected' );
		}
		catch( RangeException ){
			self::assertSame( ['md' => 6], $span->getSizes() );
		}
	}

	/**
	 *	@covers		::setSize
	 *	@return		void
	 */
	public function testSetSizeWithDefaultBreakpoint(): void
	{
		RowSpan::$defaultBreakpoint	= RowSpan::BREAKPOINT_SM;
		$span	= ( new RowSpan( 'X' ) )->setBsVersion( '5' )->setSize( 4 );
		self::assertSame( '<div class="col-sm-4">X</div>', $span->render() );
	}

	/**
	 *	@covers		::setSize
	 *	@return		void
	 */
	public function testSetSizeThrowsOnInvalidSize(): void
	{
		$this->expectException( RangeException::class );
		( new RowSpan( 'X' ) )->setSize( 13 );
	}

	/**
	 *	@covers		::setSize
	 *	@return		void
	 */
	public function testSetSizeThrowsOnInvalidBreakpoint(): void
	{
		$this->expectException( RangeException::class );
		( new RowSpan( 'X' ) )->setSize( 4, 'huge' );
	}

	protected function setUp(): void
	{
		$this->defaultBreakpoint	= RowSpan::$defaultBreakpoint;
	}

	protected function tearDown(): void
	{
		RowSpan::$defaultBreakpoint	= $this->defaultBreakpoint;
	}
}
