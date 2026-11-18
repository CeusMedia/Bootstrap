<?php
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Icon;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Icon
 * @covers			\CeusMedia\Bootstrap\Icon
 */
class IconTest extends TestCase
{
	protected string $defaultSet;
	protected string $defaultStyle;
	protected array $defaultSize;

	/**
	 *	@return		array<string,array{string,string,string|NULL,string}>
	 */
	public static function renderProvider(): array
	{
		return [
			'glyphicons'					=> ['glyphicons', 'user', NULL, 'icon-user'],
			'glyphicons white'				=> ['glyphicons', 'user', 'white', 'icon-white icon-user'],
			'fa4 short'						=> ['fontawesome', 'user', NULL, 'fa fa-user'],
			'fa4 prefixed'					=> ['fontawesome', 'fa-user', NULL, 'fa fa-user'],
			'fa4 complete'					=> ['fontawesome', 'fa fa-user', NULL, 'fa fa-user'],
			'fa5 short'						=> ['fontawesome5', 'user', NULL, 'fa fas fa-user'],
			'fa5 short regular'				=> ['fontawesome5', 'user', 'regular', 'fa far fa-user'],
			'fa5 short brand'				=> ['fontawesome5', 'github', 'brand', 'fa fab fa-github'],
			'fa5 prefixed'					=> ['fontawesome5', 'fa-user', NULL, 'fa fas fa-user'],
			'fa5 complete'					=> ['fontawesome5', 'fa fas fa-user', NULL, 'fa fas fa-user'],
			'fa5 complete without fa'		=> ['fontawesome5', 'far fa-user', NULL, 'far fa-user'],
			'fa6 short'						=> ['fontawesome6', 'user', NULL, 'fa fa-user'],
			'fa6 short solid'				=> ['fontawesome6', 'user', 'solid', 'fa-solid fa-user'],
			'fa6 complete'					=> ['fontawesome6', 'fa-solid fa-user', NULL, 'fa-solid fa-user'],
			'fa6 complete brands'			=> ['fontawesome6', 'fa-brands fa-github', NULL, 'fa-brands fa-github'],
			'fa6 complete with modifier'	=> ['fontawesome6', 'fa-solid fa-user fa-spin', NULL, 'fa-solid fa-user fa-spin'],
		];
	}

	/**
	 *	@covers		::render
	 *	@covers		::resolve
	 *	@covers		::realizeStyle
	 *	@dataProvider	renderProvider
	 *	@return		void
	 */
	public function testRender( string $set, string $icon, ?string $style, string $expectedClass ): void
	{
		Icon::$defaultSet	= $set;
		$expected	= '<i class="'.$expectedClass.'"></i>';
		self::assertEquals( $expected, Icon::create( $icon, $style )->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::realizeSizes
	 *	@return		void
	 */
	public function testRenderWithSize(): void
	{
		Icon::$defaultSet	= 'fontawesome5';
		$expected	= '<i class="fa fas fa-user fa-fw fa-2x"></i>';
		self::assertEquals( $expected, Icon::create( 'user', NULL, 'fixed x2' )->render() );
	}

	/**
	 *	@covers		::render
	 *	@covers		::resolve
	 *	@covers		::realizeSizes
	 *	@return		void
	 */
	public function testRenderCompleteWithSize(): void
	{
		Icon::$defaultSet	= 'fontawesome6';
		$expected	= '<i class="fa-solid fa-user fa-2x"></i>';
		self::assertEquals( $expected, Icon::create( 'fa-solid fa-user', NULL, 'x2' )->render() );

		$expected	= '<i class="fa-solid fa-user fa-2x"></i>';
		self::assertEquals( $expected, Icon::create( 'fa-solid fa-user fa-2x', NULL, 'x2' )->render(), 'Size must not be doubled' );

		Icon::$defaultSet	= 'fontawesome5';
		$expected	= '<i class="fa fas fa-user fa-fw"></i>';
		self::assertEquals( $expected, Icon::create( 'fa fas fa-user', NULL, 'fixed' )->render() );
	}

	protected function setUp(): void
	{
		$this->defaultSet	= Icon::$defaultSet;
		$this->defaultStyle	= Icon::$defaultStyle;
		$this->defaultSize	= Icon::$defaultSize;
	}

	protected function tearDown(): void
	{
		Icon::$defaultSet	= $this->defaultSet;
		Icon::$defaultStyle	= $this->defaultStyle;
		Icon::$defaultSize	= $this->defaultSize;
	}

	/**
	 *	@covers		::render
	 *	@covers		::realizeSizes
	 *	@covers		::realizeStyle
	 *	@return		void
	 */
	public function testRenderOtherSetsAndStyles(): void
	{
		Icon::$defaultSet	= 'my-icons';
		self::assertSame( '<i class="star large"></i>', Icon::create( 'star', NULL, 'large' )->render(), 'Unknown set: name and size are kept' );

		Icon::$defaultSet	= 'fontawesome5';
		self::assertSame( '<i class="fa fal fa-star"></i>', Icon::create( 'star', 'light' )->render() );
	}
}
