<?php
/**
 *	...
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
namespace CeusMedia\Bootstrap;
/**
 *	...
 *	@category		cmModules
 *	@package		Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2013 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 *	@since			0.3.0
 *	@version		$Id$
 */
class Link extends Component{

	protected $icon;
	protected $url;

	public function __construct( $url, $content, $class = NULL, $icon = NULL ){
		$this->setUrl( $url );
		$this->setContent( $content );
		$this->setClass( $class );
		$this->setIcon( $icon );
	}

	public function __toString(){
		try{
			return $this->render();
		}
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	public function render(){
		$attributes		= array(
			'href'		=> $this->url,
			'class'		=> $this->class,
		);
		$this->extendAttributesByData( $attributes );
		$icon	= $this->icon ? $this->icon->render().' ' : "";
		return \UI_HTML_Tag::create( 'a', $icon.$this->content, $attributes );
	}

	public function setIcon( $icon, $white = FALSE ){
		if( $icon && !( $icon instanceof Icon ) )
			$icon	= new Icon( $icon, $white );
		$this->icon	= $icon;
	}

	public function setUrl( $url ){
		$this->url	= $url;
	}
}
?>
