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
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Botton
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Submit extends \CeusMedia\Bootstrap\Component{

	protected $confirm;
	protected $icon;
	protected $name;
	protected $title;
	protected $events		= array();

	public function __construct( $name, $content, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->setName( $name );
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
			'type'		=> 'submit',
			'id'		=> $this->id,
			'name'		=> $this->name,
			'class'		=> "btn ".join( " ", $this->class ),
			'title'		=> $this->title ? addslashes( $this->title ) : NULL,
		);
		if( $this->confirm && !$this->disabled ){
			$attributes['onclick']	= 'if(!confirm(\''.addslashes( $this->confirm ).'\'))return false;';
		}
		if( $this->disabled ){
			$attributes['class']	.= " disabled";
			$attributes['disabled']	= 'disabled';
		}
		$this->extendAttributesByEvents( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return \UI_HTML_Tag::create( 'button', $icon.$this->content, $attributes );
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
	 *	@return		object		Own instance for chainability
	 */
	public function setIcon( $icon, $white = FALSE ){
		if( $icon && !( $icon instanceof \CeusMedia\Bootstrap\Icon ) ){
			$class	= join( " ", $this->class );
			$white	= preg_match( "/btn-(primary|danger|warning|info|inverse|success)/", $class );			//
			$icon	= new \CeusMedia\Bootstrap\Icon( $icon, $white );
		}
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
	public function setName( $name ){
		$this->name		= $name;
		return $this;
	}
}
?>
