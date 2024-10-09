<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Icon;

trait IconAware
{
	/**	@var	Icon|string|null		$icon  */
	protected Icon|string|null $icon		= NULL;

	/**
	 *	@param		Icon|string|NULL	$icon
	 *	@param		string|NULL			$style
	 *	@param		string|NULL			$size
	 *	@return		static				Own instance for method chaining
	 */
	public function setIcon( Icon|string|null $icon, ?string $style = NULL, ?string $size = NULL ): static
	{
		if( NULL !== $icon && !( $icon instanceof Icon ) && 0 !== strlen( $icon ) )
			$icon	= new Icon( $icon, $style, $size );
		$this->icon			= $icon;
		return $this;
	}
}
