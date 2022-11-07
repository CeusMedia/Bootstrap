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
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;

use CeusMedia\Common\Renderable;
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
class Link extends Element
{
	use AriaAware, DisabledAware, IconAware;

	protected string $url;

	/**
	 *	@param		string					$url
	 *	@param		Renderable|string|NULL	$content
	 *	@param		array|string|NULL		$class
	 *	@param		Icon|string|NULL		$icon
	 *	@param		bool					$disabled
	 */
	public function __construct( string $url, $content, $class = NULL, $icon = NULL, bool $disabled = FALSE )
	{
		parent::__construct( $content, $class );
		$this->setUrl( $url );
		if( NULL !== $icon )
			$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes		= [
			'href'		=> $this->url,
			'class'		=> $this->classes,
		];
		$this->extendAttributesByClass( $attributes );
		$this->extendAttributesByAria( $attributes );
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByData( $attributes );
		if( $this->disabled ){
			$attributes['class']		.= ' disabled';
			$this->data['attr-href']	= $attributes['href'];
			$attributes['href']			= NULL;
		}
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return HtmlTag::create( 'a', $icon.strval( $this->content ), $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function setUrl( string $url ): self
	{
		$this->url	= $url;
		return $this;
	}
}
