<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Icon;

trait IconAware
{
	/**	@var	Icon|null		$icon  */
	protected ?Icon $icon		= NULL;

	/**
	 *	@param		Icon|string		$icon
	 *	@param		string|NULL		$style
	 *	@param		string|NULL		$size
	 *	@return		self			Own instance for method chaining
	 */
	public function setIcon( $icon, ?string $style = NULL, ?string $size = NULL ): self
	{
		if( !( $icon instanceof Icon ) && strlen( $icon ) !== 0 ){
			$icon	= new Icon( $icon, $style, $size );
		}
		$this->icon			= $icon;
		return $this;
	}
}
