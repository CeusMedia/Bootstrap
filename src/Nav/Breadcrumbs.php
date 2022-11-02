<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Nav;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Link;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

use Exception;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2022 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Breadcrumbs extends Structure
{
	use ClassAware;

	protected array $crumbs		= [];
	protected string $divider;

	/**
	 *	@param		string			$divider
	 *	@param		string|array	$class
	 */
	public function __construct( string $divider = '/', $class = NULL )
	{
		parent::__construct();
		$this->setDivider( $divider );
		if( NULL !== $class )
			$this->setClass( $class );
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
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@param		Link|string			$label
	 *	@param		string|NULL			$url
	 *	@param		string|NULL			$class
	 *	@param		Icon|string|NULL	$icon
	 *	@param		boolean				$active
	 *	@return		self				Own instance for method chaining
	 */
	public function add( $label, ?string $url = NULL, ?string $class = NULL, $icon = NULL, bool $active = FALSE ): self
	{
		$this->crumbs[]	= (object) array(
			'label'		=> $label,
			'url'		=> (string) $url,
			'class'		=> (string) $class,
			'icon'		=> (string) $icon,
			'active'	=> $active,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		Link|string			$label
	 *	@param		Icon|string|NULL	$icon
	 *	@return		self		Own instance for method chaining
	 */
	public function addCurrent( $label, $icon = NULL ): self
	{
		$this->add( $label, NULL, NULL, $icon, TRUE );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for method chaining
	 */
	public function addLink( Link $link ): self
	{
		$this->add( $link );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$list		= [];
		$divider	= HtmlTag::create( 'span', "/", array( 'class' => 'divider' ) );
		foreach( $this->crumbs as $nr => $crumb ){
			if( $crumb->label instanceof Link )
				$content	= $crumb->label->render();
			else if( strlen( trim( $crumb->url ) ) ){
				$link		= new Link( $crumb->url, $crumb->label );
				$content	= $link->render();
			}
			else
				$content	= $crumb->label;

			if( version_compare( $this->bsVersion, '3', '<' ) )
				if( $nr < count( $this->crumbs ) - 1 )
					$content	.= ' '.$divider;
			$classesItem	= array( 'breadcrumb-item' );
			if( $crumb->class )
				$classesItem[]	= $crumb->class;
			if( $crumb->active )
				$classesItem[]	= 'active';
			$attributes	= array( 'class' => join( ' ', $classesItem ) );
			$icon		= "";
			if( $crumb->icon instanceof Icon )
				$icon	= $crumb->icon->render().' ';
			else if( $crumb->icon )
				$icon	= new Icon( $crumb->icon ).' ';
			$list[]	= HtmlTag::create( 'li', $icon.$content, $attributes );
		}
		$list	= HtmlTag::create( 'ul', $list, array( 'class' => 'breadcrumb' ) );
		return HtmlTag::create( 'nav', $list );
	}

	/**
	 *	@access		public
	 *	@param		string		$divider
	 *	@return		self		Own instance for method chaining
	 */
	public function setDivider( string $divider ): self
	{
		$this->divider	= $divider;
		return $this;
	}
}
