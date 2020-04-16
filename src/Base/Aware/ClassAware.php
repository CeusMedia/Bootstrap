<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait ClassAware
{
	protected $classes	= array();
	/**
	 *	Sets one or many HTML/CSS class names, given by string or array.
	 *	Appends new class names to prior added or set class names.
	 *	Accepts string with whitespace separated class names or list of class names.
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addClass( $class ): self
	{
		if( !is_array( $class ) )
			$class	= preg_split( '/\s+/', trim( $class ) );
		foreach( $class as $item )
			if( strlen( trim( $item ) ) && !in_array( $item, $this->classes ) )
				$this->classes[]	= trim( $item );
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string		$class		Class to be removed
	 *	@return		self		Own instance for chainability
	 */
	public function removeClass( $class ): self
	{
		if( strlen( trim( $class ) ) ){
			$index	= array_search( trim( $class ), $this->classes );
			if( $index !== FALSE )
				unset( $this->classes[$index] );
		}
		return $this;
	}

	/**
	 *	Sets one or many HTML/CSS class names, given by string or array.
	 *	Clears prior added or set class names.
	 *	Accepts string with whitespace separated class names or list of class names.
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setClass( $class ): self
	{
		$this->classes	= array();
		return $this->addClass( $class );
	}
}
