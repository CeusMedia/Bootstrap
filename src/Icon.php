<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Icon{

	static $iconSet		= 'glyphicons';

	public function __construct( $icon, $white = FALSE ){
		$this->icon		= $icon;
		$this->white	= $white;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(){
		try{
			$string	= $this->render();
			return $string;
		}
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(){
		$class		= $this->resolve( $this->icon );
		return \UI_HTML_Tag::create( 'i', "", array( 'class' => $class ) );
	}

	protected function resolve( $icon ){
		$parts		= explode( " ", preg_replace( "/ +/", " ", $icon ) );
		$list		= array();
		if( preg_match( '/^fa fa-/', $icon ) )
			return $icon;
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
}
?>
