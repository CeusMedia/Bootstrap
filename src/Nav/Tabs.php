<?php
/**
 *  ...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2013-2020 {@link https://ceusmedia.de/ Ceus Media}
 *  @license        http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           http://code.google.com/p/cmmodules/
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;

/**
 *  ...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2013-2020 {@link https://ceusmedia.de/ Ceus Media}
 *  @license        http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           http://code.google.com/p/cmmodules/
 */
class Tabs extends Structure
{
	protected $active		= 0;
	protected $tabs			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id			ID of tabs container
	 *	@param		integer		$active		Nr of active tab
	 *	@return		void
	 */
	public function __construct( $id, $active = 0 )
	{
		parent::__construct();
		$this->setId( $id );
//		$this->setActive( $active );
	}

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
	 *	Registers a tab (as link or fragement link with content).
	 *	ATTENTION: If you want to use dynamic tabs with content and your site is using base tag, you need to provide URLs relative to base.
	 *	@access		public
	 *	@param		string		$id			ID of tab pane container
	 *	@param		string		$url		URL of tab link
	 *	@param		string		$label		Label of tab pane
	 *	@param		string		$content	Content of tab pane, if tab is a fragment link
	 *	@param		boolean		$disabled	Flag: Do not enable this tab by default
	 *	@return		self		Own instance for chainability
	 */
	public function add( $id, $url, $label, $content = NULL, $disabled = FALSE ): self
	{
		$this->tabs[]	= (object) array(
			'id'		=> $id,
			'url'		=> $url,
			'label'		=> $label,
			'content'	=> $content,
			'disabled'	=> (boolean) $disabled,
		);
		return $this;
	}

	/**
	 *	Notes tab to be disabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to disable
	 *	@return		self		Own instance for chainability
	 */
	public function disableTab( $idOrIndex ): self
	{
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= TRUE;
		return $this;
	}

	/**
	 *	Notes tab to be enabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to enable
	 *	@return		self		Own instance for chainability
	 */
	public function enableTab( $idOrIndex ): self
	{
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= FALSE;
		return $this;
	}

	protected function getIdByIndex( $index )
	{
		foreach( $this->tabs as $nr => $item ){
			if( (int) $nr === (int) $index )
				return $item->id;
		}
		throw new \RangeException( sprintf( 'No tab available for index %d', $index ) );
	}

	protected function getTabById( $id )
	{
		foreach( $this->tabs as $nr => $tab )
			if( $tab->id === $id )
				return $this->tabs[$nr];
		throw new \RangeException( sprintf( 'No tab available for ID %s', $id ) );
	}

	/**
	 *	Renders and returns tabs.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$listTabs	= array();
		$listPanes	= array();
		if( !$this->active )
			$this->setActive( 0 );
		foreach( $this->tabs as $nr => $tab ){
			$classesItem	= array( 'nav-item' );
			$classesLink	= array( 'nav-link' );
			$classesPane	= array( 'tab-pane' );
			$dataLink		= array();
			if( $tab->id === $this->active ){
				$classesItem[]	= 'active';
				$classesPane[]	= 'show active';
			}
			$label			= $tab->label;#htmlentities( $tab->label, ENT_QUOTES, 'UTF-8' );
			$attr			= array( 'href' => $tab->url );
			if( $tab->url === '#' || $tab->url === '#'.$tab->id ){
				$attr['href']		= '#'.$tab->id;
				$dataLink['toggle'] = 'tab';
			}
			$attr['class']	= join( ' ', $classesLink );
			$link			= \UI_HTML_Tag::create( 'a', $label, $attr, $dataLink );
			if( $tab->disabled ){
				$classesItem[]	= 'disabled';
				$link			= \UI_HTML_Tag::create( 'a', $label, array( 'class' => 'nav-link' ) );
			}
			$attr			= array( 'class' => join( ' ', $classesItem ) );
			$listTabs[]		= \UI_HTML_Tag::create( 'li', $link, $attr );
			$listPanes[]	= \UI_HTML_Tag::create( 'div', $tab->content, array(
				'class'	=> join( ' ', $classesPane ),
				'id'	=> $tab->id,
				'role'	=> 'tabpanel',
			) );
		}
		$listTabs	= \UI_HTML_Tag::create( 'ul', $listTabs, array(
			'class'	=> 'nav nav-tabs',
			'id'	=> $this->id,
			'role'	=> 'tablist',
		) );
		$listTabs	= \UI_HTML_Tag::create( 'nav', $listTabs );
		$listPanes	= \UI_HTML_Tag::create( 'div', $listPanes, array( 'class' => 'tab-content' ) );
		return $listTabs.$listPanes;
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to mark as active.
	 *	@return		self		Own instance for chainability
	 */
	public function setActive( $idOrIndex ): self
	{
		$id		= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		$tab	= $this->getTabById( $id );
		if( $tab->disabled )
			throw new \RuntimeException( 'Tag with ID %s is disabled and cannot be active' );
		$this->active	= $id;
		return $this;
	}

	/**
	 *	Set ID of tabs container.
	 *	@access		public
	 *	@param		string		$id			ID of tabs container
	 *	@return		self		Own instance for chainability
	 */
	public function setId( $id ): self
	{
		$this->id	= $id;
		return $this;
	}
}
