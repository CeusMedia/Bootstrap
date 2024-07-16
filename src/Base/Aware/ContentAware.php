<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Bootstrap\Base\Aware;

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
	 *	Returns content as rendered string.
	 *	@return		string
	 */
	public function getContentAsString(): string
	{
		if( is_string( $this->content ) )
			return $this->content;
		if( $this->content instanceof Renderable )
			return $this->content->render();
		if( $this->content instanceof Stringable )
			return (string) $this->content;
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
