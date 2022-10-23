<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait IdAware
{
	protected ?string $id		= NULL;

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setId( ?string $id ): self
	{
		$this->id		= $id;
		return $this;
	}
}
