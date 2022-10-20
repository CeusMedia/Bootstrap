<?php
namespace CeusMedia\Bootstrap\Base\Aware;

trait EventAware
{
	protected $events	= array();

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setEvent( $event, $action ): self
	{
		$event	= strtolower( trim( $event ) );
		if( !isset( $this->events[$event] ) )
			$this->events[$event]	= array();
		$this->events[$event][]	= $action;
		return $this;
	}

	protected function extendAttributesByEvents( &$attributes ): self
	{
		foreach( $this->events as $event => $actions ){
			$attributes['on'.$event]	= addslashes( join( '; ', $actions ) );
		}
		return $this;
	}
}
