<?php /** @noinspection ALL */
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Label;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Label
 */
class LabelTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$text	= 'Label Text';
		$label	= new UnprotectedLabel( $text );

		self::assertEquals( $text, $label->getContent() );
		self::assertEquals( [], $label->getClasses() );

		$class	= Label::CLASS_WARNING;
		$label	= new UnprotectedLabel( $text, $class );
		self::assertEquals( [$class], $label->getClasses() );
	}

	/**
	 *	@covers		::render
	 *	@return		void
	 */
	public function testRender(): void
	{
		$text	= 'Label Text';
		$label	= new UnprotectedLabel( $text );

		$expected	= '<span class="label">Label Text</span>';
		self::assertEquals( $expected, $label->render() );

		$class	= Label::CLASS_SUCCESS;
		$label	= new UnprotectedLabel( $text, $class );

		$expected	= '<span class="label label-success">Label Text</span>';
		self::assertEquals( $expected, $label->render() );
	}
}

class UnprotectedLabel extends Label
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
