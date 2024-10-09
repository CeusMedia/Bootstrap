<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Aware;

trait IdAware
{
	protected ?string $id		= NULL;

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function setId( ?string $id ): static
	{
		$this->id		= $id;
		return $this;
	}
}
