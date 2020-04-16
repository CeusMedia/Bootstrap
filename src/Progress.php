<?php
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2018 {@link http://ceusmedia.de/ Ceus Media}
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmmodules/
 */
class Progress extends Structure
{
	use ClassAware;

	const CLASS_ACTIVE		= 'active';
	const CLASS_DANGER		= 'progress-danger';
	const CLASS_INFO		= 'progress-info';
	const CLASS_STRIPED		= 'progress-striped';
	const CLASS_SUCCESS		= 'progress-success';
	const CLASS_WARNING		= 'progress-warning';

	const BAR_CLASS_SUCCESS	= 'bar-success bg-success';
	const BAR_CLASS_INFO	= 'bar-info bg-info';
	const BAR_CLASS_WARNING	= 'bar-warning bg-warning';
	const BAR_CLASS_DANGER	= 'bar-danger bg-danger';
	const BAR_CLASS_STRIPED	= 'progress-bar-striped';

	protected $bars		= array();

	public function __construct( $class = NULL )
	{
		$this->setClass( 'progress' );
		$this->addClass( $class );
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
	 *	@return		self		Own instance for chainability
	 */
	public function addBar( $width, $class = NULL, $label = NULL ): self
	{
		$this->bars[]	= (object) array(
			'width'		=> $width,
			'class'		=> $class,
			'label'		=> (string) $label,
		);
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$list	= array();
		foreach( $this->bars as $bar ){
			$attributes	= array(
				'class'			=> 'bar progress-bar',
				'style'			=> 'width: '.$bar->width.'%',
				'role'			=> 'progressbar',
				'aria-valuemin'	=> 0,
				'aria-valuemax'	=> 100,
				'aria-valuenow'	=> round( $bar->width ),
			);
			if( $bar->class ){
				$class	= is_array( $bar->class ) ? join( ' ', $bar->class ) : $bar->class;
				$attributes['class']	.= ' '.$class;
			}
			$list[]	= \UI_HTML_Tag::create( 'div', $bar->label, $attributes );
		}
		return \UI_HTML_Tag::create( 'div', $list, array( 'class' => join( ' ', $this->classes ) ) );
	}
}
