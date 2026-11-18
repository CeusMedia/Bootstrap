<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Base\Abstraction;
use CeusMedia\Common\Renderable;
use Stringable;

trait ContentAware
{
	/**	@var	Stringable|Renderable|string|array|NULL		$content  */
	protected Stringable|Renderable|string|array|NULL	$content	= NULL;

	/**
	 *	Returns set content or NULL.
	 *	@access		public
	 *	@return		Stringable|Renderable|string|array|NULL
	 */
	public function getContent(): Stringable|Renderable|string|array|NULL
	{
		return $this->content;
	}

	/**
	 *	Returns content as string.
	 *	Content may be a string, a component or a list of both.
	 *	Components of this library are rendered for the Bootstrap version of the containing component.
	 *	@access		public
	 *	@return		string
	 */
	public function getContentAsString(): string
	{
		$items	= is_array( $this->content ) ? $this->content : [$this->content];
		$list	= [];
		foreach( $items as $item )
			$list[]	= $this->renderContentItem( $item );
		return join( '', $list );
	}

	/**
	 *	@param		mixed		$item		String, number, component or NULL
	 *	@return		string
	 */
	protected function renderContentItem( mixed $item ): string
	{
		if( $item instanceof Abstraction && $this instanceof Abstraction ){
			$item	= clone $item;													//  do not change given component
			$item->setBsVersion( $this->getBsVersion() );
		}
		if( $item instanceof Renderable )
			return $item->render();
		if( $item instanceof Stringable || is_string( $item ) || is_int( $item ) || is_float( $item ) )
			return (string) $item;
		return '';
	}

	/**
	 *	@access		public
	 *	@param		Stringable|Renderable|string|array|NULL		$content
	 *	@return		static			Own instance for method chaining
	 */
	public function setContent( Stringable|Renderable|string|array|null $content ): static
	{
		$this->content	= $content;
		return $this;
	}
}
