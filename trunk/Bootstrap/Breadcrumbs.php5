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
class CMM_Bootstrap_Breadcrumbs{

	protected $crumbs;
	protected $class;
	protected $divider;

	public function __construct( $divider = "/", $class = NULL ){
		$this->setDivider( $divider );
		$this->setClass( $class );
	}

	public function add( $label, $url = NULL, $class = NULL, $icon = NULL, $active = FALSE ){
		$this->crumbs[]	= (object) array(
			'label'		=> (string) $label,
			'url'		=> (string) $url,
			'class'		=> (string) $class,
			'icon'		=> (string) $icon,
			'active'	=> (bool) $active,
		);
	}

	public function addCurrent( $label, $icon = NULL ){
		$this->add( $label, NULL, NULL, $icon, TRUE );
	}

	public function addLink( CMM_Bootstrap_Link $link ){
		$this->add( $link, NULL, NULL, NULL, FALSE );
	}

	public function setClass( $class ){
		$this->class	= $class;
	}

	public function setDivider( $divider ){
		$this->divider	= $divider;
	}

	public function render(){
		$list		= array();
		$divider	= UI_HTML_Tag::create( 'span', "/", array( 'class' => 'divider' ) );
		foreach( $this->crumbs as $nr => $crumb ){
			if( $crumb->label instanceof CMM_Bootstrap_Link )
				$content	= $crumb->label->render();
			else if( strlen( trim( $crumb->url ) ) ){
				$attributes	= array( 'href' => $crumb->url );
				$content	= UI_HTML_Tag::create( 'a', $crumb->label, $attributes );
			}
			else
				$content	= $crumb->label;

			if( $nr < count( $this->crumbs ) - 1 )
				$content	.= ' '.$divider;
			$attributes	= array( 'class' => $crumb->class );
			if( $crumb->active )
				$attributes['class']	.= ' active';
			$icon	= "";
			if( $crumb->icon instanceof CMM_Bootstrap_Icon )
				$icon	= $crumb->icon->render().' ';
			else if( $crumb->icon )
				$icon	= new CMM_Bootstrap_Icon( $crumb->icon ).' ';
			$list[]	= UI_HTML_Tag::create( 'li', $icon.$content, $attributes );
		}
		return UI_HTML_Tag::create( 'ul', $list, array( 'class' => 'breadcrumb' ) );
	}

	public function __toString(){
		return $this->render();
	}
}
?>
