<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait DisabledAware
{
	protected bool $disabled		= FALSE;

	/**
	 *	@access		public
	 *	@param		boolean		$disabled
	 *	@return		self		Own instance for method chaining
	 */
	public function setDisabled( bool $disabled = TRUE ): self
	{
		$this->disabled	= $disabled;
		return $this;
	}

}
