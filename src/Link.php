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

use CeusMedia\Bootstrap\Base\Component;
use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link extends Component
{
	use AriaAware, IconAware;

	protected $url;

	public function __construct( $url, $content, $class = NULL, $icon = NULL )
	{
		parent::__construct( $content, $class );
		$this->setUrl( $url );
		$this->setIcon( $icon );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes		= array(
			'href'		=> $this->url,
			'class'		=> $this->classes,
		);
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByAria( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return \UI_HTML_Tag::create( 'a', $icon.$this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setUrl( $url ): self
	{
		$this->url	= $url;
		return $this;
	}
}
