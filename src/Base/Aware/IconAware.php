<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Icon;

trait IconAware
{
	protected $icon		= NULL;

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setIcon( $icon, $white = FALSE ): self
	{
		if( $icon && !( $icon instanceof Icon ) ){
			$icon	= new Icon( $icon, $white );
		}
		$this->icon	= $icon;
		return $this;
	}
}
