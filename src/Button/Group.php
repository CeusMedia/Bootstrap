<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Button;

use CeusMedia\Bootstrap\Base\Structure;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap_Button
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Group extends Structure
{
	protected $buttons		= array();
	protected $stacked		= FALSE;
	protected $class		= "";

	public function __construct( $buttons = array(), $stacked = FALSE )
	{
		parent::__construct();
		$this->add( $buttons );
		$this->setStacked( $stacked );
	}

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
	public function add( $button ): self
	{
		if( is_array( $button ) )
			foreach( $button as $item )
				$this->add( $item );
		else if( $button )
			$this->buttons[]	= $button;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$classes		= array( 'btn-group' );
		if( $this->stacked )
			$classes[]	= 'btn-group-vertical';
		if( strlen( trim( $this->class ) ) )
			$classes[]	= trim( $this->class );
		$attributes		= array( 'class' => join( " ", $classes ) );
		return \UI_HTML_Tag::create( 'div', $this->buttons, $attributes );
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setClass( $class ): self
	{
		$this->class	= $class;
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		self		Own instance for chainability
	 */
	public function setStacked( $stacked = TRUE ): self
	{
		$this->stacked		= $stacked;
		return $this;
	}
}
