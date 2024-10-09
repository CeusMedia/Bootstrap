<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use function is_bool;
use function htmlentities;
use function strtolower;

trait AriaAware
{
	protected array $ariaAttributes	= [];
	protected ?string $role			= NULL;

	/**
	 *	@access		public
	 *	@param		string			$key		...
	 *	@param		string|bool		$value		...
	 *	@return		static			Own instance for method chaining
	 */
	public function setAria( string $key, bool|string $value ): static
	{
		$key	= strtolower( $key );
		if( is_bool( $value ) )
			$value	= $value ? 'true' : 'false';
		$this->ariaAttributes[$key]	= $value;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function setRole( string $role ): static
	{
		$this->role	= strtolower( $role );
		return $this;
	}

	protected function extendAttributesByAria( array &$attributes ): self
	{
		foreach( $this->ariaAttributes as $key => $value ){
			$attributes['aria-'.$key]	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		}
		if( NULL !== $this->role )
			$attributes['role']	= $this->role;
		return $this;
	}
}
