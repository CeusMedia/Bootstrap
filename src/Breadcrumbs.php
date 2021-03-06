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
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Bootstrap\Link;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2020 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Breadcrumbs extends Structure
{
	use ClassAware;

	protected $crumbs;
	protected $divider;

	public function __construct( $divider = "/", $class = NULL )
	{
		parent::__construct();
		$this->setDivider( $divider );
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
		catch( \Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@return		object		Own instance for chainability
	 */
	public function add( $label, $url = NULL, $class = NULL, $icon = NULL, $active = FALSE ): self
	{
		$this->crumbs[]	= (object) array(
			'label'		=> $label,
			'url'		=> (string) $url,
			'class'		=> (string) $class,
			'icon'		=> (string) $icon,
			'active'	=> (bool) $active,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addCurrent( $label, $icon = NULL ): self
	{
		$this->add( $label, NULL, NULL, $icon, TRUE );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function addLink( Link $link ): self
	{
		$this->add( $link, NULL, NULL, NULL, FALSE );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$list		= array();
		$divider	= \UI_HTML_Tag::create( 'span', "/", array( 'class' => 'divider' ) );
		foreach( $this->crumbs as $nr => $crumb ){
			if( $crumb->label instanceof Link )
				$content	= $crumb->label->render();
			else if( strlen( trim( $crumb->url ) ) ){
				$link		= new Link( $crumb->url, $crumb->label );
				$content	= $link->render();
			}
			else
				$content	= $crumb->label;

			if( version_compare( $this->bsVersion, 3, '<' ) )
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
			$list[]	= \UI_HTML_Tag::create( 'li', $icon.$content, $attributes );
		}
		$list	= \UI_HTML_Tag::create( 'ul', $list, array( 'class' => 'breadcrumb' ) );
		return \UI_HTML_Tag::create( 'nav', $list );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setDivider( $divider ): self
	{
		$this->divider	= $divider;
		return $this;
	}
}
