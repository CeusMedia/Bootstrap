<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2015 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2015 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Icon{

	static $iconSet		= 'glyphicons';

	public function __construct( $icon, $white = FALSE ){
		$this->icon		= $icon;
		$this->white	= $white;
	}

	protected function resolve( $icon ){
		$parts	= explode( " ", preg_replace( "/ +/", " ", $icon ) );
		$list	= array();
		foreach( $parts as $part ){
			switch( strtolower( self::$iconSet ) ){
				case 'glyphicons':
					$part	= "icon-".$part;
					break;
				case 'fontawesome':
					return 'fa fa-fw fa-'.$part;
					break;
			}
			$list[]		= $part;
		}
		if( $this->white ){
			switch( strtolower( self::$iconSet ) ){
				case 'glyphicons':
					$list[]	= 'icon-white';
					break;
			}
		}
		return join( " ", $list );
	}

	public function render(){
		$class		= $this->resolve( $this->icon );
		return \UI_HTML_Tag::create( 'i', "", array( 'class' => $class ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
