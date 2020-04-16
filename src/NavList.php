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
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class NavList{

	protected $current;

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(){
		try{
			return $this->render();
		}
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function add( $url, $label, $icon = NULL, $class = NULL, $attr = array(), $data = array(), $events = array() ){
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'url'		=> $url,
			'label'		=> $label,
			'icon'		=> $icon,
			'class'		=> $class,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function addDivider(){
		$this->items[]	= (object) array(
			'type'		=> 'divider',
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function addHeader( $label, $icon = NULL, $class = NULL ){
		$this->items[]	= (object) array(
			'type'		=> 'header',
			'label'		=> $label,
			'icon'		=> $icon,
			'class'		=> trim( 'nav-header autocut '.$class ),
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function addNavList( NavList $list ){
		$this->items[]	= (object) array(
			'type'		=> 'navlist',
			'list'		=> $list,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		foreach( $this->items as $item ){
			switch( $item->type ){
				case 'divider':
					$list[]	= \UI_HTML_Tag::create( 'li', "", array( 'class' => 'divider' ) );
					break;
				case 'header':
					$label	= $item->label;
					if( $item->icon )
						$label	= new Icon( $item->icon ).' '.$label;
					$list[]	= \UI_HTML_Tag::create( 'li', $label, array( 'class' => $item->class) );
					break;
				case 'navlist':
					$list[]	= $item->list->render();
					break;
				case 'link':
					$attr	= array(
						'class' => array( '' ),
						'title' => $item->label
					);
					$invert	= FALSE;
					if( $item->url == $this->current ){
						$attr['class'][]	= 'active';
						$invert	= TRUE;
					}
					$link	= new Link( $item->url, $item->label, 'autocut' );
					$link->setIcon( $item->icon, $invert );
					$attr['class']	= join( " ", $attr['class'] );
					$list[]	= \UI_HTML_Tag::create( 'li', $link, $attr );
					break;
			}
		}
		return \UI_HTML_Tag::create( 'ul', $list, array( 'class' => 'nav nav-list' ) );
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function setCurrent( $url ){
		$this->current	= $url;
		return $this;
	}
}
?>
