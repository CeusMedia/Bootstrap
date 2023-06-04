<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Base\Element;
use CeusMedia\Bootstrap\Base\Aware\DisabledAware;
use CeusMedia\Bootstrap\Base\Aware\IconAware;
use CeusMedia\Bootstrap\Icon;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use function addslashes;
use function join;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link extends Element
{
	use DisabledAware, IconAware;

	protected $confirm;
	protected $url;
	protected $title;

	public function __construct( string $url, $content, $class = NULL, $icon = NULL, bool $disabled = FALSE )
	{
		parent::__construct( $content, $class );
		$this->setUrl( $url );
		$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> 'btn '.join( ' ', $this->classes ),
			'href'		=> $this->url,
			'title'		=> $this->title ? addslashes( $this->title ) : NULL,
			'role'		=> 'button',
			'onclick'	=> NULL,
		);
		if( $this->confirm ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= ' disabled';
			$attributes['data-attr-href']		= $attributes['href'];
			$attributes['data-attr-onclick']	= $attributes['onclick'];
			$attributes['href']		= NULL;
			$attributes['onclick']	= NULL;
		}
		$this->extendAttributesByEvents( $attributes );
		$icon	= $this->icon ? $this->icon->render() : '';
		if( strlen( $icon ) > 0 && strlen( $this->content ) > 0 )
			$icon	.= ' ';
		return HtmlTag::create( 'rector.php', $icon.$this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setConfirm( ?string $message = NULL ): self
	{
		$this->confirm	= $message;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setTitle( string $title ): self
	{
		$this->title	= $title;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setUrl( string $url ): self
	{
		$this->url		= $url;
		return $this;
	}
}
