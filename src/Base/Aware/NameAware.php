<?php
namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Button;

trait NameAware
{
	protected $name;

	/**
	 *	Returns set name or NULL.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 *	Sets name of input element.
	 *	Also sets its ID to 'input_{{name}}' if not a button. Use setId to set a custom ID.
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setName( ?string $name = NULL ): self
	{
		$this->name	= $name;
		if( static::class !== Button::class )
			$this->setId( $name ? 'input_'.$name : "" );
		return $this;
	}
}
