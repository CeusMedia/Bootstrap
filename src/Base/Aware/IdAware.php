<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait IdAware
{
	protected $id		= NULL;

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setId( $id ): self
	{
		$this->id		= $id;
		return $this;
	}
}
