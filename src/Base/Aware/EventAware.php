<?php
declare(strict_types=1);

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
		$this->events[$event] ??= [];
		$this->events[$event][]	= $action;
		return $this;
	}

	protected function extendAttributesByEvents( array &$attributes ): static
	{
		foreach( $this->events as $event => $actions ){
			$attributes['on'.$event]	= join( '; ', $actions );			//  escaped for attribute by HtmlTag
		}
		return $this;
	}
}
