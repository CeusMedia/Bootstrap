<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait EventAware
{
	protected array $events	= [];

	/**
	 *	@access		public
	 *	@param		string		$event		...
	 *	@param		string		$action		...
	 *	@return		static		Own instance for method chaining
	 */
	public function setEvent( string $event, string $action ): static
	{
		$event	= strtolower( trim( $event ) );
		if( !isset( $this->events[$event] ) )
			$this->events[$event]	= [];
		$this->events[$event][]	= $action;
		return $this;
	}

	protected function extendAttributesByEvents( array &$attributes ): self
	{
		foreach( $this->events as $event => $actions ){
			$attributes['on'.$event]	= addslashes( join( '; ', $actions ) );
		}
		return $this;
	}
}
