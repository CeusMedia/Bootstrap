<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use function is_bool;
use function htmlentities;
use function strtolower;

trait AriaAware
{
	protected $ariaAttributes	= array();
	protected $role;

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setAria( $key, $value ): self
	{
		$key	= strtolower( $key );
		if( is_bool( $value ) )
			$value	= $value ? 'true' : 'false';
		$this->ariaAttributes[$key]	= $value;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setRole( $role ): self
	{
		$this->role	= strtolower( $role );
		return $this;
	}

	protected function extendAttributesByAria( &$attributes ): self
	{
		foreach( $this->ariaAttributes as $key => $value ){
			$attributes['aria-'.$key]	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		}
		if( $this->role )
			$attributes['role']	= $this->role;
		return $this;
	}
}
