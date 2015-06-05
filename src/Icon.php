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

	public function __construct( $icon, $white = FALSE ){
		$this->icon		= $icon;
		$this->white	= $white;
	}

	public function render(){
		$class	= 'icon-'.$this->icon;
		if( $this->white )
			$class	.= ' icon-white';
		return \UI_HTML_Tag::create( 'i', "", array( 'class' => $class ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
