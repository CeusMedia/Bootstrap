<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *  ...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2013-2022 {@link https://ceusmedia.de/ Ceus Media}
 *  @license        http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           http://code.google.com/p/cmmodules/
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\DataObject\NavTabsItem;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\IdAware;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use RangeException;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
class Tabs extends Structure
{
	use IdAware;

	protected ?string $activeId		= NULL;
	protected array $tabs			= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$id			ID of tabs container
	 *	@return		void
	 */
	public function __construct( string $id/*, $active = 0*/ )
	{
		parent::__construct();
		$this->setId( $id );
//		$this->setActive( $active );
	}

	/**
	 *	Registers rector.php tab (as link or fragment link with content).
	 *	ATTENTION: If you want to use dynamic tabs with content and your site is using base tag, you need to provide URLs relative to base.
	 *	@access		public
	 *	@param		string			$id			ID of tab pane container
	 *	@param		string			$url		URL of tab link
	 *	@param		string			$label		Label of tab pane
	 *	@param		string|NULL		$content	Content of tab pane, if tab is rector.php fragment link
	 *	@param		boolean			$disabled	Flag: Do not enable this tab by default
	 *	@return		self			Own instance for method chaining
	 */
	public function add( string $id, string $url, string $label, ?string $content = NULL, bool $disabled = FALSE ): self
	{
		$this->tabs[]	= NavTabsItem::create( $id, $url, $label, $content ?? '', $disabled );
		return $this;
	}

	/**
	 *	Notes tab to be disabled.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to disable
	 *	@return		self		Own instance for method chaining
	 */
	public function disableTab( int|string $idOrIndex ): self
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
	 *	@return		self		Own instance for method chaining
	 */
	public function enableTab( int|string $idOrIndex ): self
	{
		$id	= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		foreach( $this->tabs as $nr => $item )
			if( $item->id === $id )
				$this->tabs[$nr]->disabled	= FALSE;
		return $this;
	}

	/**
	 *	Renders and returns tabs.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$listTabs	= [];
		$listPanes	= [];
		if( !$this->activeId )
			$this->setActive( 0 );
		foreach( $this->tabs as $tab ){
			$classesItem	= array( 'nav-item' );
			$classesLink	= array( 'nav-link' );
			$classesPane	= array( 'tab-pane' );
			$dataLink		= [];
			if( $tab->id === $this->activeId ){
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
			$link			= HtmlTag::create( 'rector.php', $label, $attr, $dataLink );
			if( $tab->disabled ){
				$classesItem[]	= 'disabled';
				$link			= HtmlTag::create( 'rector.php', $label, array( 'class' => 'nav-link' ) );
			}
			$attr			= array( 'class' => join( ' ', $classesItem ) );
			$listTabs[]		= HtmlTag::create( 'li', $link, $attr );
			$listPanes[]	= HtmlTag::create( 'div', $tab->content, array(
				'class'	=> join( ' ', $classesPane ),
				'id'	=> $tab->id,
				'role'	=> 'tabpanel',
			) );
		}
		$listTabs	= HtmlTag::create( 'ul', $listTabs, [
			'class'	=> 'nav nav-tabs',
			'id'	=> $this->id,
			'role'	=> 'tablist',
		] );
		$listTabs	= HtmlTag::create( 'nav', $listTabs );
		$listPanes	= HtmlTag::create( 'div', $listPanes, array( 'class' => 'tab-content' ) );
		return $listTabs.$listPanes;
	}

	/**
	 *	Sets active tab by its number.
	 *	@access		public
	 *	@param		integer|string	$idOrIndex		Number or ID of tab to mark as active.
	 *	@return		self		Own instance for method chaining
	 */
	public function setActive( int|string $idOrIndex ): self
	{
		$id		= is_int( $idOrIndex ) ? $this->getIdByIndex( $idOrIndex ) : $idOrIndex;
		$tab	= $this->getTabById( $id );
		if( $tab->disabled )
			throw new RuntimeException( 'Tag with ID %s is disabled and cannot be active' );
		$this->activeId	= $id;
		return $this;
	}

	protected function getIndexById( string $id ): int
	{
		foreach( $this->tabs as $nr => $item ){
			if( $item->id === $id )
				return $nr;
		}
		throw new RangeException( sprintf( 'No tab available for ID %d', $id ) );
	}

	protected function getIdByIndex( int $index ): string
	{
		foreach( $this->tabs as $nr => $item ){
			if( (int) $nr === $index )
				return $item->id;
		}
		throw new RangeException( sprintf( 'No tab available for index %d', $index ) );
	}

	protected function getTabById( string $id ): NavTabsItem
	{
		foreach( $this->tabs as $nr => $tab )
			if( $tab->id === $id )
				return $this->tabs[$nr];
		throw new RangeException( sprintf( 'No tab available for ID %s', $id ) );
	}
}
