<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Common\Renderable;

trait ContentAware
{
	/**	@var	Renderable|string|NULL		$content  */
	protected ?string $content	= NULL;

	/**
	 *	Returns set content or NULL.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}

	/**
	 *	@access		public
	 *	@param		Renderable|string|NULL		$content
	 *	@return		self			Own instance for method chaining
	 */
	public function setContent( ?string $content ): self
	{
		$this->content	= $content;
		return $this;
	}
}
