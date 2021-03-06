<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait DisabledAware
{
	protected $disabled		= FALSE;

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setDisabled( $disabled = TRUE ): self
	{
		$this->disabled	= $disabled;
		return $this;
	}

}
