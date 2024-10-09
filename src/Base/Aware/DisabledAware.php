<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Aware;

trait DisabledAware
{
	protected bool $disabled		= FALSE;

	/**
	 *	@access		public
	 *	@param		boolean		$disabled
	 *	@return		static		Own instance for method chaining
	 */
	public function setDisabled( bool $disabled = TRUE ): static
	{
		$this->disabled	= $disabled;
		return $this;
	}

}
