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

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Base\StateClassParser;
use CeusMedia\Bootstrap\Base\Element;

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
class Badge extends Element
{
	public const string CLASS_IMPORTANT	= 'badge-important badge-danger';
	public const string CLASS_INVERSE		= 'badge-inverse';
	public const string CLASS_INFO		= 'badge-info';
	public const string CLASS_SUCCESS		= 'badge-success';
	public const string CLASS_WARNING		= 'badge-warning';

	/** @var array<string,string> Background colors of Bootstrap 3 labels, applied to badges, which have no colors in Bootstrap 3 */
	protected const array COLORS_BOOTSTRAP_3	= [
		'primary'	=> '#337ab7',
		'success'	=> '#5cb85c',
		'info'		=> '#5bc0de',
		'warning'	=> '#f0ad4e',
		'danger'	=> '#d9534f',
		'dark'		=> '#333333',
	];

	public const array CLASSES			= [
		self::CLASS_IMPORTANT,
		self::CLASS_INVERSE,
		self::CLASS_INFO,
		self::CLASS_SUCCESS,
		self::CLASS_WARNING,
	];

	/**
	 *	Renders badge for set Bootstrap version.
	 *	Bootstrap 2 has colored badges (like "badge-important"), Bootstrap 3 has gray badges only (colors of labels are applied by style),
	 *	Bootstrap 4 has colors like "badge-danger", Bootstrap 5 uses background utilities like "bg-danger".
	 *	Since Bootstrap 4, badges are rendered as pills, to keep their round shape,
	 *	and get secondary color by default, since they would be invisible otherwise.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$context	= new StateClassParser( $this->classes, ['badge', 'bg', 'text-bg'] );
		$classes	= ['badge'];
		$style		= NULL;
		switch( $version ){
			case BsVersion::V2:
				$classes[]	= match( $context->state ){
					NULL, 'secondary', 'light'	=> '',
					'danger'					=> 'badge-important',
					'dark'						=> 'badge-inverse',
					'primary'					=> 'badge-info',
					default						=> 'badge-'.$context->state,
				};
				break;
			case BsVersion::V3:													//  no badge colors, so colors of labels are applied
				$color	= self::COLORS_BOOTSTRAP_3[$context->state ?? ''] ?? NULL;
				$style	= NULL !== $color ? 'background-color: '.$color : NULL;
				break;
			case BsVersion::V4:
			case BsVersion::V5:
				$classes	= array_merge( $classes, self::realizeColorClasses( $version, $context->state ?? 'secondary' ) );
				$classes[]	= BsVersion::V4 === $version ? 'badge-pill' : 'rounded-pill';
				break;
		}
		$classes	= array_filter( array_merge( $classes, $context->others ), static fn( string $class ): bool => '' !== $class );
		$attributes	= [
			'id'	=> $this->id,
			'class'	=> join( ' ', array_unique( $classes ) ),
			'style'	=> $style,
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		return HtmlTag::create( 'span', $this->getContentAsString(), $attributes );
	}

	/**
	 *	Returns class names of badge color for Bootstrap 4 or 5, used by Badge and Label.
	 *	Bootstrap 5 has no badge colors anymore but background utilities, needing dark text on light backgrounds.
	 *	@access		public
	 *	@static
	 *	@param		BsVersion	$version
	 *	@param		string		$state		Neutral state, see StateClassParser
	 *	@return		array<string>
	 */
	public static function realizeColorClasses( BsVersion $version, string $state ): array
	{
		return match( $version ){
			BsVersion::V2, BsVersion::V3	=> [],
			BsVersion::V4					=> ['badge-'.$state],
			BsVersion::V5					=> in_array( $state, ['light', 'warning', 'info'], TRUE )
				? ['bg-'.$state, 'text-dark']
				: ['bg-'.$state],
		};
	}
}
