<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\DataObject\NavListItem;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Exception\Data\Missing as DataMissingException;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use Exception;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2024 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class NavList extends Structure
{
	protected ?string $current		= NULL;

	/** @var NavListItem[] $items */
	protected array $items			= [];

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
	 *	@param		URL|string			$url
	 *	@param		string				$label
	 *	@param		Icon|string|NULL	$icon
	 *	@param		string|NULL			$class
	 *	@return		static				Own instance for method chaining
	 */
	public function add( URL|string $url, string $label, Icon|string $icon = NULL, ?string $class = NULL/*, array $attr = [], $data = [], $events = []*/ ): static
	{
		$item	= new NavListItem();
		$item->type		= 'link';
		$item->url		= $url;
		$item->label	= $label;
		$item->icon		= is_string( $icon ) ? new Icon( $icon ) : $icon;
		$item->class	= $class;

		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addDivider(): static
	{
		$item	= new NavListItem();
		$item->type		= 'divider';
		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string				$label
	 *	@param		Icon|string|NULL	$icon
	 *	@param		string|NULL			$class
	 *	@return		static				Own instance for method chaining
	 */
	public function addHeader( string $label, Icon|string|null $icon = NULL, string $class = NULL ): static
	{
		$item	= new NavListItem();
		$item->type		= 'header';
		$item->label	= $label;
		$item->icon		= is_string( $icon ) ? new Icon( $icon ) : $icon;
		$item->class	= trim( 'nav-header autocut '.$class );

		$this->items[]	= $item;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		static		Own instance for method chaining
	 */
	public function addNavList( NavList $list ): static
	{
		$item	= new NavListItem();
		$item->type		= 'navlist';
		$item->list		= $list;
		$this->items[]	= $item;
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
					$list[]	= HtmlTag::create( 'li', "", ['class' => 'divider'] );
					break;
				case 'header':
					$label	= $item->label;
					if( NULL !== $item->icon )
						$label	= $item->icon->render().' '.$label;
					$list[]	= HtmlTag::create( 'li', $label, ['class' => $item->class] );
					break;
				case 'navlist':
					if( NULL !== $item->list )
						$list[]	= $item->list->render();
					break;
				case 'link':
					if( NULL === $item->url )
						throw new DataMissingException( 'No URL provided for link' );
					$attr	= [
						'class' => explode( ' ', $item->class ?? '' ),
						'title' => $item->label
					];
					$invert	= FALSE;
					if( $item->url === $this->current ){
						$attr['class'][]	= 'active';
						$invert	= TRUE;
					}
					$link	= new Link( $item->url, $item->label, 'autocut' );
					$link->setIcon( $item->icon, $invert ? 'white' : '' );
					$attr['class']	= join( " ", $attr['class'] );
					$list[]	= HtmlTag::create( 'li', $link, $attr );
					break;
			}
		}
		return HtmlTag::create( 'ul', $list, ['class' => 'nav nav-list'] );
	}

	/**
	 *	@access		public
	 *	@param		string		$url
	 *	@return		static		Own instance for method chaining
	 */
	public function setCurrent( string $url ): static
	{
		$this->current	= $url;
		return $this;
	}
}
