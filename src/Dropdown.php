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
class Dropdown{

	protected $items		= array();
	protected $alignLeft	= TRUE;

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

	public function add( $url, $label, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'content'	=> new \CeusMedia\Bootstrap\Link( $url, $label, $class, $icon, $disabled ),
/*			'class'		=> $class,
			'icon'		=> $icon,
			'disabled'	=> $disabled,*/
		);
	}

	public function addDivider(){
		$this->items[]	= (object) array(
			'type'		=> 'divider',
			'content'	=> NULL,
		);
	}

	public function addDropdown( $label, Dropdown $dropdown, $class = NULL, $icon = NULL, $disabled = FALSE ){
		$this->items[]	= (object) array(
			'type'		=> 'dropdown',
			'content'	=> $label,
			'submenu'	=> $dropdown,
			'class'		=> $class,
			'icon'		=> $icon,
			'disabled'	=> $disabled,
		);
	}

	public function addLink( $link, $disabled = FALSE ){
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'content'	=> $link,
			'disabled'	=> $disabled,
		);
	}

	public function setAlign( $left = TRUE ){
		$this->alignLeft	= $left;
	}

	public function setAriaLabel( $label ){
		$this->ariaLabel	= $label;
	}

	public function render(){
		$list	= array();
		foreach( $this->items as $item ){
			$attributes	= array( 'class' => NULL );# 'class' => 'active' ); 
			switch( $item->type ){
				case "dropdown":
					$attributes['class']	= 'dropdown-submenu';
					$item->content	= $item->content.$item->submenu->render();
					break;
				case "divider":
					$attributes['class']	= 'divider'; 
					break;
				case "link":
					break;
				default:
					throw new OutOfBoundsException( 'Invalid dropdown item time: '.$item->type );
			}
			if( $item->disabled )
				$attributes['class']	.= ' disabled'; 
			$list[]	= \UI_HTML_Tag::create( 'li', $item->content, $attributes );
		}
		$attributes	= array(
			'class'		=> "dropdown-menu",
		);
		if( !$this->alignLeft )
			$attributes['class']	= $attributes['class'].' pull-right';
		return \UI_HTML_Tag::create( 'ul', $list, $attributes );
	}
}
?>
