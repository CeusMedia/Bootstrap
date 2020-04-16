<?php
namespace CeusMedia\Bootstrap\Base\Aware;

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
		$key	= \strtolower( $key );
		if( \is_bool( $value ) )
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
		$this->role	= \strtolower( $role );
		return $this;
	}

	protected function extendAttributesByAria( &$attributes ): self
	{
		foreach( $this->ariaAttributes as $key => $value ){
			$attributes['on'.$event]	= addslashes( join( '; ', $actions ) );
		}
		if( $this->role )
			$attributes['role']	= $this->role;
		return $this;
	}
}
