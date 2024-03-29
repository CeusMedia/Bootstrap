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
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Exception;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class NavList extends Structure
{
	protected $current;
	protected $items		= [];

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component or exception message
	 */
	public function __toString(): string
	{
		try{
			return $this->render();
		}
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function add( string $url, string $label, $icon = NULL, ?string $class = NULL/*, array $attr = array(), $data = array(), $events = array()*/ ): self
	{
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
	 *	@return		self		Own instance for chainability
	 */
	public function addDivider(): self
	{
		$this->items[]	= (object) array(
			'type'		=> 'divider',
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addHeader( string $label, $icon = NULL, string $class = NULL ): self
	{
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
	 *	@return		self		Own instance for chainability
	 */
	public function addNavList( NavList $list ): self
	{
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
	public function render(): string
	{
		$list	= [];
		foreach( $this->items as $item ){
			switch( $item->type ){
				case 'divider':
					$list[]	= HtmlTag::create( 'li', "", array( 'class' => 'divider' ) );
					break;
				case 'header':
					$label	= $item->label;
					if( $item->icon )
						$label	= new Icon( $item->icon ).' '.$label;
					$list[]	= HtmlTag::create( 'li', $label, array( 'class' => $item->class) );
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
					$list[]	= HtmlTag::create( 'li', $link, $attr );
					break;
			}
		}
		return HtmlTag::create( 'ul', $list, array( 'class' => 'nav nav-list' ) );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setCurrent( string $url ): self
	{
		$this->current	= $url;
		return $this;
	}
}
