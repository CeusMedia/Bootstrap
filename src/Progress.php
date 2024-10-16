<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */
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
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\DataObject\NavTabsItem;
use CeusMedia\Bootstrap\Base\DataObject\ProgressBar;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
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
class Progress extends Structure
{
	use ClassAware;

	public const CLASS_ACTIVE		= 'active';
	public const CLASS_DANGER		= 'progress-danger';
	public const CLASS_INFO			= 'progress-info';
	public const CLASS_STRIPED		= 'progress-striped';
	public const CLASS_SUCCESS		= 'progress-success';
	public const CLASS_WARNING		= 'progress-warning';

	public const BAR_CLASS_SUCCESS	= 'bar-success bg-success';
	public const BAR_CLASS_INFO		= 'bar-info bg-info';
	public const BAR_CLASS_WARNING	= 'bar-warning bg-warning';
	public const BAR_CLASS_DANGER	= 'bar-danger bg-danger';
	public const BAR_CLASS_STRIPED	= 'progress-bar-striped';

	/** @var array<ProgressBar> $bars */
	protected array $bars		= [];

	public function __construct( ?string $class = NULL )
	{
		parent::__construct();
		$this->setClass( 'progress' );
		if( NULL !== $class )
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
		catch( Exception $e ){
			print $e->getMessage();
			exit;
		}
	}

	/**
	 *	@access		public
	 *	@param		float			$width		Values: 0-100
	 *	@param		string|NULL		$class
	 *	@param		string|NULL		$label
	 *	@return		self		Own instance for method chaining
	 */
	public function addBar( float $width, ?string $class = NULL, ?string $label = NULL ): self
	{
		$this->bars[]	= ProgressBar::create( $width, $class, $label );
		return $this;
	}

	/**
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$list	= [];
		foreach( $this->bars as $bar ){
			$attributes	= [
				'class'			=> 'bar progress-bar',
				'style'			=> 'width: '.$bar->width.'%',
				'role'			=> 'progressbar',
				'aria-valuemin'	=> 0,
				'aria-valuemax'	=> 100,
				'aria-valuenow'	=> round( $bar->width ),
			];
			if( 0 !== strlen( $bar->class ?? '' ) )
				$attributes['class']	.= ' '.$bar->class;
			$list[]	= HtmlTag::create( 'div', $bar->label, $attributes );
		}
		return HtmlTag::create( 'div', $list, ['class' => join( ' ', $this->classes )] );
	}
}
