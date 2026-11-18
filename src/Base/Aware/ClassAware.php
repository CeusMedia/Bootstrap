<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Aware;

trait ClassAware
{
	protected array $classes	= [];

	/**
	 *	Sets one or many HTML/CSS class names, given by string or array.
	 *	Appends new class names to prior added or set class names.
	 *	Accepts string with whitespace separated class names or list of class names.
	 *	@access		public
	 *	@param		string|array<string>	$class
	 *	@return		static			Own instance for method chaining
	 */
	public function addClass( array|string $class ): static
	{
		$classes	= [];
		if( is_array( $class ) )
			$classes	= $class;
		else{
			$list		= preg_split( '/\s+/', trim( $class ) );
			if( FALSE !== $list )
				$classes	= $list;
		}
		foreach( $classes as $item ){
			$item	= trim( $item );
			if( '' !== $item && !in_array( $item, $this->classes, TRUE ) )
				$this->classes[]	= trim( $item );
		}
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string		$class		Class to be removed
	 *	@return		static		Own instance for method chaining
	 */
	public function removeClass( string $class ): static
	{
		if( '' !== trim( $class ) ){
			$index	= array_search( trim( $class ), $this->classes, TRUE );
			if( $index !== FALSE )
				unset( $this->classes[$index] );
			$this->classes	= array_values( $this->classes );
		}
		return $this;
	}

	/**
	 *	Sets one or many HTML/CSS class names, given by string or array.
	 *	Clears prior added or set class names.
	 *	Accepts string with whitespace separated class names or list of class names.
	 *	@access		public
	 *	@param		array|string|NULL	$class
	 *	@return		static				Own instance for method chaining
	 */
	public function setClass( array|string|null $class = NULL ): static
	{
		$this->classes	= [];
		if( NULL !== $class )
			$this->addClass( $class );
		return $this;
	}

	/**
	 *	@param		array		$attributes
	 *	@return		static
	 */
	protected function extendAttributesByClass( array &$attributes ): static
	{
		$attributes['class']	= join( ' ', $this->classes );
		return $this;
	}
}
