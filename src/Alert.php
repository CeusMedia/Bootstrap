<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\AlertClassResolver;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Element;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Alert extends Element
{
	/*  Constants of any version can be used, they are translated for set version (see AlertClassResolver).
	 *  States missing in a version are shown as info (Bootstrap 2 and 3: primary, secondary, light, dark).  */
	public const string CLASS_PRIMARY		= "alert-primary";			//  since Bootstrap 4
	public const string CLASS_SECONDARY	= "alert-secondary";		//  since Bootstrap 4

	public const string CLASS_SUCCESS		= "alert-success";
	public const string CLASS_INFO			= "alert-info";
	public const string CLASS_WARNING		= "alert-warning";
	public const string CLASS_DANGER		= "alert-danger";
	public const string CLASS_INVERSE		= "alert-inverse";			//  same as CLASS_DARK

	public const string CLASS_LIGHT		= "alert-light";			//  since Bootstrap 4
	public const string CLASS_DARK			= "alert-dark";				//  since Bootstrap 4

	public const string CLASS_ERROR		= "alert-error";			//  Bootstrap 2 name, same as CLASS_DANGER

	protected bool $useDismiss	= FALSE;

	/**
	 *	Renders alert for set Bootstrap version.
	 *	States of any version are translated (like "alert-error" of Bootstrap 2 to "alert-danger"), see AlertClassResolver.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$content	= $this->getContentAsString();
		if( $this->useDismiss )
			$content	= match( $version ){
				BsVersion::V2, BsVersion::V3	=> $this->renderDismissButton( $version ).$content,
				BsVersion::V4, BsVersion::V5	=> $content.$this->renderDismissButton( $version ),
			};
		$classes	= AlertClassResolver::resolve( $version, $this->classes, $this->useDismiss );
		$attributes	= [
			'id'	=> $this->id,
			'class'	=> join( ' ', $classes ),
			'role'	=> 'alert',
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		return HtmlTag::create( 'div', $content, $attributes );
	}

	/**
	 *	Enables or disables dismiss button, which is disabled by default.
	 *	@access		public
	 *	@param		boolean		$use		Flag: enable or disable dismiss button, default: enable
	 *	@return		static		Own instance for method chaining
	 */
	public function useDismiss( bool $use = TRUE ): static
	{
		$this->useDismiss = $use;
		return $this;
	}

	/*  --  PROTECTED  --  */

	protected function renderDismissButton( BsVersion $version ): string
	{
		$dialect	= $this->dialect();
		return match( $version ){
			BsVersion::V2	=> HtmlTag::create( 'a', '&times;', [
				'href'						=> '#',
				'class'						=> 'close',
				$dialect->data( 'dismiss' )	=> 'alert',
			] ),
			BsVersion::V3, BsVersion::V4	=> HtmlTag::create( 'button', HtmlTag::create( 'span', '&times;', ['aria-hidden' => 'true'] ), [
				'type'						=> 'button',
				'class'						=> 'close',
				$dialect->data( 'dismiss' )	=> 'alert',
				'aria-label'				=> 'Close',
			] ),
			BsVersion::V5	=> HtmlTag::create( 'button', '', [
				'type'						=> 'button',
				'class'						=> 'btn-close',
				$dialect->data( 'dismiss' )	=> 'alert',
				'aria-label'				=> 'Close',
			] ),
		};
	}
}
