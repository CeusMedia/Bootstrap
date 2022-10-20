<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait ContentAware
{
	protected $content	= NULL;

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
	 *	@return		self		Own instance for method chaining
	 */
	public function setContent( $content ): self
	{
		$this->content	= $content;
		return $this;
	}
}
