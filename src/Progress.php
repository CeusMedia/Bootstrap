<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */
declare(strict_types=1);

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap;

use CeusMedia\Bootstrap\Base\DataObject\ProgressBar;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Structure;
use CeusMedia\Bootstrap\Base\Aware\ClassAware;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class Progress extends Structure
{
	use ClassAware;

	public const string CLASS_ACTIVE		= 'active';
	public const string CLASS_DANGER		= 'progress-danger';
	public const string CLASS_INFO			= 'progress-info';
	public const string CLASS_STRIPED		= 'progress-striped';
	public const string CLASS_SUCCESS		= 'progress-success';
	public const string CLASS_WARNING		= 'progress-warning';

	public const string BAR_CLASS_SUCCESS	= 'bar-success bg-success';
	public const string BAR_CLASS_INFO		= 'bar-info bg-info';
	public const string BAR_CLASS_WARNING	= 'bar-warning bg-warning';
	public const string BAR_CLASS_DANGER	= 'bar-danger bg-danger';
	public const string BAR_CLASS_STRIPED	= 'progress-bar-striped';

	/** @var array<ProgressBar> $bars */
	/** @var array<string> Supported color states */
	protected const array STATES	= ['success', 'info', 'warning', 'danger'];

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
	 *	@param		float			$width		Values: 0-100
	 *	@param		string|NULL		$class
	 *	@param		string|NULL		$label
	 *	@return		static			Own instance for method chaining
	 */
	public function addBar( float $width, ?string $class = NULL, ?string $label = NULL ): static
	{
		$this->bars[]	= ProgressBar::create( $width, $class, $label );
		return $this;
	}

	/**
	 *	Renders progress for set Bootstrap version.
	 *	Colors, striping and animation can be given by class names of any version
	 *	(like constants BAR_CLASS_* and CLASS_*) and are translated:
	 *	Bootstrap 2 uses "bar bar-danger" and stripes/animation on container,
	 *	Bootstrap 3 uses "progress-bar progress-bar-danger",
	 *	Bootstrap 4 and 5 use "progress-bar bg-danger".
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version			= $this->getBsVersion();
		$containerClasses	= [];
		$containerState		= NULL;
		$striped			= FALSE;
		$animated			= FALSE;
		foreach( $this->classes as $class ){
			if( 1 === preg_match( '/^progress-('.join( '|', self::STATES ).')$/', $class, $matches ) )
				$containerState	= $matches[1];
			else if( 'progress-striped' === $class )
				$striped	= TRUE;
			else if( 'active' === $class )
				$animated	= TRUE;
			else
				$containerClasses[]	= $class;
		}

		$list	= [];
		foreach( $this->bars as $bar ){
			$barState		= NULL;
			$barStriped		= $striped;
			$barAnimated	= $animated;
			$barClasses		= [];
			$tokens			= preg_split( '/\s+/', trim( $bar->class ?? '' ), -1, PREG_SPLIT_NO_EMPTY );
			foreach( FALSE !== $tokens ? $tokens : [] as $token ){
				if( 1 === preg_match( '/^(bar|bg|progress-bar)-('.join( '|', self::STATES ).')$/', $token, $matches ) )
					$barState	= $matches[2];
				else if( 'progress-bar-striped' === $token )
					$barStriped	= TRUE;
				else if( in_array( $token, ['active', 'progress-bar-animated'], TRUE ) )
					$barAnimated	= TRUE;
				else
					$barClasses[]	= $token;
			}
			$barState	??= $containerState;
			if( BsVersion::V2 === $version ){											//  Bootstrap 2: stripes and animation on container only
				$striped	= $striped || $barStriped;
				$animated	= $animated || $barAnimated;
			}
			$attributes	= [
				'class'			=> join( ' ', array_merge( $this->realizeBarClasses( $version, $barState, $barStriped, $barAnimated ), $barClasses ) ),
				'style'			=> 'width: '.$bar->width.'%',
				'role'			=> 'progressbar',
				'aria-valuemin'	=> 0,
				'aria-valuemax'	=> 100,
				'aria-valuenow'	=> round( $bar->width ),
			];
			$list[]	= HtmlTag::create( 'div', $bar->label, $attributes );
		}
		if( BsVersion::V2 === $version ){
			if( $striped )
				$containerClasses[]	= 'progress-striped';
			if( $animated )
				$containerClasses[]	= 'active';
		}
		return HtmlTag::create( 'div', $list, ['class' => join( ' ', $containerClasses )] );
	}

	/*  --  PROTECTED  --  */

	/**
	 *	@param		BsVersion		$version
	 *	@param		string|NULL		$state			One of STATES or NULL
	 *	@param		bool			$striped
	 *	@param		bool			$animated
	 *	@return		array<string>
	 */
	protected function realizeBarClasses( BsVersion $version, ?string $state, bool $striped, bool $animated ): array
	{
		return array_values( array_filter( match( $version ){
			BsVersion::V2	=> [
				'bar',
				NULL !== $state ? 'bar-'.$state : '',
			],
			BsVersion::V3	=> [
				'progress-bar',
				NULL !== $state ? 'progress-bar-'.$state : '',
				$striped || $animated ? 'progress-bar-striped' : '',
				$animated ? 'active' : '',
			],
			BsVersion::V4, BsVersion::V5	=> [
				'progress-bar',
				NULL !== $state ? 'bg-'.$state : '',
				$striped || $animated ? 'progress-bar-striped' : '',
				$animated ? 'progress-bar-animated' : '',
			],
		}, static fn( string $class ): bool => '' !== $class ) );
	}
}
