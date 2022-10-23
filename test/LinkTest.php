<?php
namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
	public function testConstruct()
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

	public function getContent(): string
	{
		return $this->content;
	}

	public function getUrl(): string
	{
		return $this->url;
	}
}
