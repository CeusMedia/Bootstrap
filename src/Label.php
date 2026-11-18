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
class Label extends Element
{
	public const string CLASS_IMPORTANT	= 'label-important';
	public const string CLASS_INVERSE		= 'label-inverse';
	public const string CLASS_INFO		= 'label-info';
	public const string CLASS_SUCCESS		= 'label-success';
	public const string CLASS_WARNING		= 'label-warning';

	public const array CLASSES			= [
		self::CLASS_IMPORTANT,
		self::CLASS_INVERSE,
		self::CLASS_INFO,
		self::CLASS_SUCCESS,
		self::CLASS_WARNING,
	];

	/**
	 *	Renders label for set Bootstrap version.
	 *	Bootstrap 2 and 3 have labels with different color names (like "label-important" and "label-danger"),
	 *	Bootstrap 3 needs "label-default" to be visible.
	 *	Since Bootstrap 4, labels are replaced by badges.
	 *	@access		public
	 *	@return		string		Rendered HTML of component
	 */
	public function render(): string
	{
		$version	= $this->getBsVersion();
		$context	= new StateClassParser( $this->classes, ['label', 'badge', 'bg', 'text-bg'] );
		$state		= $context->state;
		$classes	= match( $version ){
			BsVersion::V2	=> ['label', match( $state ){
				NULL, 'secondary', 'light'	=> '',
				'danger'					=> 'label-important',
				'dark'						=> 'label-inverse',
				'primary'					=> 'label-info',
				default						=> 'label-'.$state,
			}],
			BsVersion::V3	=> ['label', match( $state ){
				NULL, 'secondary', 'light', 'dark'	=> 'label-default',
				default								=> 'label-'.$state,
			}],
			BsVersion::V4, BsVersion::V5	=> array_merge( ['badge'], Badge::realizeColorClasses( $version, $state ?? 'secondary' ) ),
		};
		$classes	= array_filter( array_merge( $classes, $context->others ), static fn( string $class ): bool => '' !== $class );
		$attributes	= [
			'id'	=> $this->id,
			'class'	=> join( ' ', array_unique( $classes ) ),
		];
		$this->extendAttributesByData( $attributes );
		$this->extendAttributesByEvents( $attributes );
		return HtmlTag::create( 'span', $this->getContentAsString(), $attributes );
	}
}
