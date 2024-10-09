<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Bootstrap\Base\Aware;

use CeusMedia\Common\Alg\Text\CamelCase;
use DomainException;

trait DataAware
{
	/** @var array<string,mixed> $data */
	protected array $data		= [];

	/**
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		mixed		$value		...
	 *	@param		boolean		$strict		...
	 *	@return		static		Own instance for method chaining
	 *	@throws		DomainException		if key is already set and strict mode is enabled
	 */
	public function setData( string $key, mixed $value, bool $strict = TRUE ): static
	{
		$key	= CamelCase::decode( $key );
		$key	= str_replace( ' ', '-', strtolower( $key ) );
		if( $strict && array_key_exists( $key, $this->data ) )
			throw new DomainException( 'Data for key "'.$key.'" already set' );
		$this->data[$key]	= $value;
		return $this;
	}

	protected function extendAttributesByData( array &$attributes ): self
	{
		foreach( $this->data as $key => $value )
			$attributes['data-'.strtolower( $key )]	= htmlentities( $value, ENT_QUOTES, 'UTF-8' );
		return $this;
	}
}
