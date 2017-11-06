<?php
/**
 *  ...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2013 {@link http://ceusmedia.de/ Ceus Media}
 *  @license        http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           http://code.google.com/p/cmmodules/
 *  @since          0.3.0
 *  @version        $Id$
 */
namespace CeusMedia\Bootstrap;
/**
 *  ...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2013 {@link http://ceusmedia.de/ Ceus Media}
 *  @license        http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           http://code.google.com/p/cmmodules/
 *  @since          0.3.0
 *  @version        $Id$
 */
class Tabs{

	protected $active		= 0;
	protected $tabs			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id			ID of tabs container
	 *	@param		integer		$active		Nr of active tab
	 *	@return		void
	 */
	public function __construct( $id, $active = 0 ){
		$this->setId( $id );
//		$this->setActive( $active );
	}

	public function __toString(){
		return $this->render();
	}

	/**
	 *	Registers a tab (as link or fragement link with content).
	 *	ATTENTION: If you want to use dynamic tabs with content and your site is using base tag, you need to provide URLs relative to base.
	 *	@access		public
	 *	@param		string		$id			ID of tab pane container
	 *	@param		string		$url		URL of tab link
	 *	@param		string		$label		Label of tab pane
	 *	@param		string		$content	Content of tab pane, if tab is a fragment link
	 *	@param		boolean		$disabled	Flag: Do not enable this tab by default
	 *	@return		void
	 */
	public function add( $id, $url, $label, $content = NULL, $disabled = FALSE ){
		$this->tabs[]	= (object) array(
			'id'		=> $id,
			'url'		=> $url,
			'label'		=> $label,
			'content'	=> $content,
			'disabled'	=> (boolean) $disabled,
		);
	}

	/**
	 *	Notes tab to be disabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to disable
	 *	@return		void
	 */
	public function disableTab( $idOrIndex ){
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= TRUE;
	}

	/**
	 *	Notes tab to be enabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to enable
	 *	@return		void
	 */
	public function enableTab( $idOrIndex ){
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= FALSE;
	}

	protected function getIdByIndex( $index ){
		foreach( $this->tabs as $nr => $item ){
			if( (int) $nr === (int) $index )
				return $item->id;
		}
		throw new \RangeException( sprintf( 'No tab available for index %d', $index ) );
	}

	protected function getTabById( $id ){
		foreach( $this->tabs as $nr => $tab )
			if( $tab->id === $id )
				return $this->tabs[$nr];
		throw new \RangeException( sprintf( 'No tab available for ID %s', $id ) );
	}

	/**
	 *	Renders and returns tabs.
	 *	@access		public
	 *	@return		string
	 */
	public function render(){
		$listTabs	= array();
		$listPanes	= array();
		foreach( $this->tabs as $nr => $tab ){
			$classesItem	= array();
			$classesPane	= array( 'tab-pane' );
			if( $tab->id === $this->active ){
				$classesItem[]	= 'active';
				$classesPane[]	= 'active';
			}
			$label			= $tab->label;#htmlentities( $tab->label, ENT_QUOTES, 'UTF-8' );
			$attr			= array( 'href' => $tab->url );
			if( $tab->url === '#' || $tab->url === '#'.$tab->id )
				$attr		= array( 'href' => '#'.$tab->id, 'data-toggle' => 'tab' );
			$link			= \UI_HTML_Tag::create( 'a', $label, $attr );
			if( $tab->disabled ){
				$classesItem[]	= 'disabled';
				$link			= \UI_HTML_Tag::create( 'a', $label, array() );
			}
			$attr			= array( 'class' => join( ' ', $classesItem ) );
			$listTabs[]		= \UI_HTML_Tag::create( 'li', $link, $attr );
			$attr			= array( 'class' => join( ' ', $classesPane ), 'id' => $tab->id );
			$listPanes[]	= \UI_HTML_Tag::create( 'div', $tab->content, $attr );
		}
		$listTabs	= \UI_HTML_Tag::create( 'ul', $listTabs, array( 'class' => 'nav nav-tabs', 'id' => $this->id ) );
		$listPanes	= \UI_HTML_Tag::create( 'div', $listPanes, array( 'class' => 'tab-content' ) );
		return $listTabs.$listPanes;
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to mark as active.
	 *	@return
	 */
	public function setActive( $idOrIndex ){
		$id		= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		$tab	= $this->getTabById( $id );
		if( $tab->disabled )
			throw new \RuntimeException( 'Tag with ID %s is disabled and cannot be active' );
		$this->active	= $id;
	}

	/**
	 *	Set ID of tabs container.
	 *	@access		public
	 *	@param		string		$id			ID of tabs container
	 *	@return void
	 */
	public function setId( $id ){
		$this->id	= $id;
	}
}
?>
