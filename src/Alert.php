<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;

use UI_HTML_Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Alert extends Element
{
	const CLASS_PRIMARY		= "alert-primary";			// only BS4
	const CLASS_SECONDARY	= "alert-secondary";		// only BS4

	const CLASS_SUCCESS		= "alert-success";
	const CLASS_INFO		= "alert-info";
	const CLASS_WARNING		= "alert-warning";
	const CLASS_DANGER		= "alert-danger";
	const CLASS_INVERSE		= "alert-inverse";			// ?

	const CLASS_LIGHT		= "alert-light";			// only BS4
	const CLASS_DARK		= "alert-dark";				// only BS4

	const CLASS_ERROR		= "alert-error";			// BS2, not BS4 - fallback for DANGER

	protected $useDismiss	= FALSE;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$class	= 'alert';
		if( count( $this->classes ) )
			$class	.= ' '.join( ' ', $this->classes );

		$dismiss	= '';
		if( $this->useDismiss ){
			$dismiss = HtmlTag::create( 'a', '&times;', [
				'href'	=> '#',
				'class'	=> 'close',
			], ['dismiss' => 'alert'] );
		}
		return HtmlTag::create( 'div', $dismiss.$this->content, [
			'class'	=> $class,
			'role'	=> 'alert',
		] );
	}

	/**
	 *	Enables or disables dismiss button, which is disabled by default.
	 *	@access		public
	 *	@param		boolean		$use		Flag: enable or disable dismiss button, default: enable
	 *	@return		self		Own instance for chainability
	 */
	public function useDismiss( bool $use = TRUE ): self
	{
		$this->useDismiss = $use;
		return $this;
	}
}
