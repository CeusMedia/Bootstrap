<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Link;
use CeusMedia\Common\Renderable;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @coversDefaultClass	\CeusMedia\Bootstrap\Link
 */
class LinkTest extends TestCase
{
	/**
	 *	@covers		::__construct
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		$href	= 'https://example.com/link';
		$label	= 'Link Label';
		$class	= 'btn btn-small btn-danger';
		$link	= new UnprotectedLink( $href, $label, $class );

		self::assertEquals( $href, $link->getUrl() );
		self::assertEquals( $label, $link->getContent() );
		self::assertEquals( explode( ' ', $class ), $link->getClasses() );
	}
}

class UnprotectedLink extends Link
{
	public function getClasses(): array
	{
		return $this->classes;
	}

	public function getContent(): Renderable|Stringable|array|string|NULL
	{
		return $this->content;
	}

	public function getUrl(): string
	{
		return $this->url;
	}
}
