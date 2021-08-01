<?php
use CeusMedia\Bootstrap\Link;

class LinkTest extends PHPUnit\Framework\TestCase
{
	public function testConstruct()
	{
		$href	= 'https://example.com/link';
		$label	= 'Link Label';
		$class	= 'btn btn-small btn-danger';
		$link	= new UnprotectedLink( $href, $label, $class );

		$this->assertEquals( $href, $link->getUrl() );
		$this->assertEquals( $label, $link->getContent() );
		$this->assertEquals( explode( ' ', $class ), $link->getClasses() );
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
