<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Component;
use CeusMedia\Bootstrap\Icon;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Link extends Component{

	protected $confirm;
	protected $icon;
	protected $url;
	protected $title;
	protected $events		= array();

	public function __construct( $url, $content, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->setUrl( $url );
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setIcon( $icon );
		$this->setDisabled( $disabled );
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> "btn ".join( " ", $this->class ),
			'href'		=> $this->url,
			'title'		=> $this->title ? addslashes( $this->title ) : NULL,
			'onclick'	=> NULL,
		);
		if( $this->confirm ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= " disabled";
			$attributes['data-attr-href']		= $attributes['href'];
			$attributes['data-attr-onclick']	= $attributes['onclick'];
			$attributes['href']		= NULL;
			$attributes['onclick']	= NULL;
		}
		$this->extendAttributesByEvents( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return \UI_HTML_Tag::create( 'a', $icon.$this->content, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setConfirm( $message = NULL ){
		$this->confirm	= $message;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setDisabled( $disabled = TRUE ){
		$this->disabled	= $disabled;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string			$icon 		Icon class name plus modifying class names
	 *	@param		string			$style 		Icon set style (see code doc of Icon::setStyle)
	 *	@param		string|array	$size 		One or many size or modifier class name (see code doc of Icon::setSize)
	 *	@return		object			Own instance for chainability
	 */
	public function setIcon( $icon, $style = NULL, $size = NULL ){
		if( is_string( $icon ) )
			$icon	= new Icon( $icon, $style, $size );
		$this->icon	= $icon;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setTitle( $title ){
		$this->title	= $title;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setUrl( $url ){
		$this->url		= $url;
		return $this;
	}
}
?>
