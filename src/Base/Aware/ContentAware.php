<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait ContentAware
{
	protected $content	= NULL;

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setContent( $content ): self
	{
		$this->content	= $content;
		return $this;
	}
}
