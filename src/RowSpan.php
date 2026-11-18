<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
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

use CeusMedia\Bootstrap\Base\Aware\AriaAware;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\Element;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use RangeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
class RowSpan extends Element
{
	use AriaAware;

	public const string BREAKPOINT_XS	= 'xs';
	public const string BREAKPOINT_SM	= 'sm';
	public const string BREAKPOINT_MD	= 'md';
	public const string BREAKPOINT_LG	= 'lg';
	public const string BREAKPOINT_XL	= 'xl';
	public const string BREAKPOINT_XXL	= 'xxl';

	/** @var array<string> Breakpoints, ordered by width */
	public const array BREAKPOINTS		= [
		self::BREAKPOINT_XS,
		self::BREAKPOINT_SM,
		self::BREAKPOINT_MD,
		self::BREAKPOINT_LG,
		self::BREAKPOINT_XL,
		self::BREAKPOINT_XXL,
	];

	/** @var string Breakpoint used by setSize without breakpoint */
	public static string $defaultBreakpoint	= self::BREAKPOINT_MD;

	/** @var array<string,int> Map of breakpoint to column size */
	protected array $sizes	= [];

	/**
	 *	Renders column for set Bootstrap version.
	 *	Bootstrap 2 has no breakpoints and uses "span{size}" of the breakpoint closest to "md".
	 *	Later versions use "col-{breakpoint}-{size}", with "col-{size}" for "xs" since Bootstrap 4.
	 *	Breakpoints not existing in a version fall back to the largest existing one:
	 *	Bootstrap 3 has "xs" to "lg", Bootstrap 4 has "xs" to "xl", Bootstrap 5 has "xs" to "xxl".
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$sizes		= [] !== $this->sizes ? $this->sizes : [static::$defaultBreakpoint => 12];
		$classes	= match( $version ){
			BsVersion::V2	=> ['span'.$this->getSizeClosestToMedium( $sizes )],
			BsVersion::V3, BsVersion::V4, BsVersion::V5	=> $this->realizeColumnClasses( $version, $sizes ),
		};
		$attributes		= [
			'id'		=> $this->id,
			'class'		=> join( ' ', array_merge( $classes, $this->classes ) ),
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		$this->extendAttributesByAria( $attributes );
		return HtmlTag::create( 'div', $this->getContentAsString(), $attributes );
	}

	/**
	 *	Returns map of breakpoint to column size, ordered by breakpoint width.
	 *	@access		public
	 *	@return		array<string,int>
	 */
	public function getSizes(): array
	{
		return $this->sizes;
	}

	/**
	 *	Sets size of column for a breakpoint.
	 *	Call several times to set sizes for several breakpoints, like 12 for "xs" and 6 for "md".
	 *	@access		public
	 *	@param		integer			$size			Size of column (1-12)
	 *	@param		string|NULL		$breakpoint		One of BREAKPOINTS, default: static::$defaultBreakpoint
	 *	@return		static			Own instance for method chaining
	 *	@throws		RangeException	if size or breakpoint is invalid
	 */
	public function setSize( int $size, ?string $breakpoint = NULL ): static
	{
		$breakpoint	??= static::$defaultBreakpoint;
		if( $size < 1 || $size > 12 )
			throw new RangeException( 'Invalid column size: '.$size );
		if( !in_array( $breakpoint, self::BREAKPOINTS, TRUE ) )
			throw new RangeException( 'Invalid breakpoint: '.$breakpoint );
		$this->sizes[$breakpoint]	= $size;
		uksort( $this->sizes, static fn( string $a, string $b ): int =>
			array_search( $a, self::BREAKPOINTS, TRUE ) <=> array_search( $b, self::BREAKPOINTS, TRUE ) );
		return $this;
	}

	/**
	 *	Sets sizes of column for several breakpoints at once, replacing sizes set before.
	 *	Example: setSizes( ['xs' => 12, 'md' => 6, 'xl' => 4] )
	 *	@access		public
	 *	@param		array<string,int>	$sizes		Map of breakpoint (one of BREAKPOINTS) to size (1-12)
	 *	@return		static			Own instance for method chaining
	 *	@throws		RangeException	if a size or breakpoint is invalid
	 */
	public function setSizes( array $sizes ): static
	{
		$previous		= $this->sizes;
		$this->sizes	= [];
		try{
			foreach( $sizes as $breakpoint => $size )
				$this->setSize( $size, $breakpoint );
		}
		catch( RangeException $e ){
			$this->sizes	= $previous;										//  keep state on invalid input
			throw $e;
		}
		return $this;
	}

	/*  --  PROTECTED  --  */

	/**
	 *	Returns size for Bootstrap 2, which has no breakpoints.
	 *	Prefers "md", then smaller, then larger breakpoints.
	 *	@param		array<string,int>	$sizes
	 *	@return		int
	 */
	protected function getSizeClosestToMedium( array $sizes ): int
	{
		foreach( ['md', 'sm', 'lg', 'xs', 'xl', 'xxl'] as $breakpoint )
			if( isset( $sizes[$breakpoint] ) )
				return $sizes[$breakpoint];
		return 12;
	}

	/**
	 *	@param		BsVersion			$version
	 *	@param		array<string,int>	$sizes		Ordered by breakpoint width
	 *	@return		array<string>
	 */
	protected function realizeColumnClasses( BsVersion $version, array $sizes ): array
	{
		$largest	= match( $version ){
			BsVersion::V2	=> 'md',
			BsVersion::V3	=> 'lg',
			BsVersion::V4	=> 'xl',
			BsVersion::V5	=> 'xxl',
		};
		$maxIndex	= (int) array_search( $largest, self::BREAKPOINTS, TRUE );
		$realized	= [];
		foreach( $sizes as $breakpoint => $size ){										//  exact breakpoints first
			if( array_search( $breakpoint, self::BREAKPOINTS, TRUE ) <= $maxIndex )
				$realized[$breakpoint]	= $size;
		}
		foreach( $sizes as $breakpoint => $size ){										//  then fallbacks, if not set exactly
			if( array_search( $breakpoint, self::BREAKPOINTS, TRUE ) > $maxIndex )
				$realized[$largest]	??= $size;
		}
		$classes	= [];
		foreach( self::BREAKPOINTS as $breakpoint ){
			if( !isset( $realized[$breakpoint] ) )
				continue;
			$classes[]	= BsVersion::V3 !== $version && self::BREAKPOINT_XS === $breakpoint
				? 'col-'.$realized[$breakpoint]
				: 'col-'.$breakpoint.'-'.$realized[$breakpoint];
		}
		return $classes;
	}
}
