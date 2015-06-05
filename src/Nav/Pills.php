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
namespace CeusMedia\Bootstrap\Nav;
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
class Pills{

	protected $active	= -1;
	protected $items	= array();

	public function __toString(){
		try{
			return $this->render();
		}
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	public function add( $url, $label, $class = NULL, $icon = NULL ){
		$link	= new \CeusMedia\Bootstrap\Link( $url, $label, $class, $icon );
		$this->addLink( $link );
	}

	public function addLink( \CeusMedia\Bootstrap\Link $link ){
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'link'		=> $link,
		);
	}

	public function addDropdown( \CeusMedia\Bootstrap\Dropdown $dropdown, $label, $class = NULL, $icon = NULL, $iconActive = NULL ){
		$this->items[]	= (object) array(
			'type'			=> 'dropdown',
			'label'			=> $label,
			'content'		=> $dropdown,
			'class'			=> $class,
			'icon'			=> $icon,
			'iconActive'	=> $iconActive,
		);
	}

	public function render(){
		$items	= array();
		foreach( $this->items as $nr => $item ){
			$class		= $this->active === $nr ? "active" : NULL;
			if( $item->type === "dropdown" ){
				$icon		= $this->active === $nr && $item->iconActive ? $item->iconActive : $item->icon;
				$trigger	= new \CeusMedia\Bootstrap\Dropdown\Trigger\Link( $item->label, $item->class, $icon );
				$item		= \UI_HTML_Tag::create( 'li', $trigger.$item->content, array( 'class' => 'dropdown '.$class ) );
			}
			else{
				$item	= \UI_HTML_Tag::create( 'li', (string) $item->link, array( 'class' => $class ) );
			}
			$items[]	= $item;
		}
		return \UI_HTML_Tag::create( 'div', $items, array( 'class' => 'nav nav-pills' ) );
	}

	public function setActive( $nr ){
		$this->active	= $nr;
	}

	public function setStacked( $stacked = TRUE ){
		$this->stacked	= (bool) $stacked;
	}
}
?>
