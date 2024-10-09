<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Bootstrap\Button;

trait NameAware
{
	protected ?string $name		= NULL;

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
	 *	@param		string|NULL		$name		Name of input elementname of input element.
	 *	@return		static			Own instance for method chaining
	 */
	public function setName( ?string $name = NULL ): static
	{
		$this->name	= $name;
		if( !is_a( $this, Button::class ) )
			$this->setId( $name ? 'input_'.$name : "" );
		return $this;
	}
}
