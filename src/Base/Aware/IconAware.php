<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Icon;

trait IconAware
{
	/**	@var	Icon|null		$icon  */
	protected ?Icon $icon		= NULL;

	/**
	 *	@access		public
	 *	@param		Icon|string|NULL	$icon
	 *	@param		bool				$white
	 *	@return		self				Own instance for method chaining
	 */
	public function setIcon( $icon, bool $white = FALSE ): self
	{
		if( !( $icon instanceof Icon ) && strlen( $icon ) !== 0 ){
			$icon	= new Icon( $icon, $white );
		}
		$this->icon	= $icon;
		return $this;
	}
}
