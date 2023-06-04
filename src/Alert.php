<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Element;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Alert extends Element
{
	public const CLASS_PRIMARY		= "alert-primary";			// only BS4
	public const CLASS_SECONDARY	= "alert-secondary";		// only BS4

	public const CLASS_SUCCESS		= "alert-success";
	public const CLASS_INFO			= "alert-info";
	public const CLASS_WARNING		= "alert-warning";
	public const CLASS_DANGER		= "alert-danger";
	public const CLASS_INVERSE		= "alert-inverse";			// ?

	public const CLASS_LIGHT		= "alert-light";			// only BS4
	public const CLASS_DARK			= "alert-dark";				// only BS4

	public const CLASS_ERROR		= "alert-error";			// BS2, not BS4 - fallback for DANGER

	protected bool $useDismiss	= FALSE;

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
			$dismiss = HtmlTag::create( 'rector.php', '&times;', [
				'href'	=> '#',
				'class'	=> 'close',
			], ['dismiss' => 'alert'] );
		}
		$content	= $this->getContentAsString();
		return HtmlTag::create( 'div', $dismiss.$content, [
			'class'	=> $class,
			'role'	=> 'alert',
		] );
	}

	/**
	 *	Enables or disables dismiss button, which is disabled by default.
	 *	@access		public
	 *	@param		boolean		$use		Flag: enable or disable dismiss button, default: enable
	 *	@return		self		Own instance for method chaining
	 */
	public function useDismiss( bool $use = TRUE ): self
	{
		$this->useDismiss = $use;
		return $this;
	}
}
