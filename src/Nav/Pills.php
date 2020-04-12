<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Nav
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Nav
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Pills extends Structure
{
	protected $active	= -1;
	protected $items	= array();

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
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
	 *	@return		self		Own instance for chainability
	 */
	public function add( $url, $label, $class = NULL, $icon = NULL ): self
	{
		$class	= 'nav-link'.( $class ? ' '.$class : '' );
		$link	= new \CeusMedia\Bootstrap\Link( $url, $label, $class, $icon );
		$this->addLink( $link );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addLink( \CeusMedia\Bootstrap\Link $link ): self
	{
		$this->items[]	= (object) array(
			'type'		=> 'link',
			'link'		=> $link,
			'class'		=> 'nav-item',
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addDropdown( \CeusMedia\Bootstrap\Dropdown $dropdown, $label, $class = NULL, $icon = NULL, $iconActive = NULL ): self
	{
		if( version_compare( static::$version, 4, '>=' ) )
			$label		= \UI_HTML_Tag::create( 'a', $label, array(
				'href'			=> '#',
				'class'			=> 'nav-link dropdown-toggle',
				'data-toggle'	=> 'dropdown',
			) );
		$this->items[]	= (object) array(
			'type'			=> 'dropdown',
			'label'			=> $label,
			'content'		=> $dropdown,
			'class'			=> 'nav-item'.( $class ? ' '.$class : '' ),
			'icon'			=> $icon,
			'iconActive'	=> $iconActive,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
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

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setActive( $nr ): self
	{
		$this->active	= $nr;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setStacked( $stacked = TRUE ): self
	{
		$this->stacked	= (bool) $stacked;
		return $this;
	}
}
?>
