<?php
declare(strict_types=1);

/**
 *	Translates button class names between Bootstrap versions.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

/**
 *	Translates button class names between Bootstrap versions.
 *
 *	Reads state (color), outline, size and block from class names of any version,
 *	like "btn-default" (2/3) or "btn-secondary" (4+), "btn-inverse" (2) or "btn-dark" (4+),
 *	"btn-mini" (2), "btn-xs" (3) or "btn-sm" (3+), and renders class names of given version.
 *	Buttons without state get the default variant of the version, since from Bootstrap 3 on,
 *	a plain "btn" has no background and border.
 *	Other class names are kept as they are.
 *
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class ButtonClassResolver
{
	/** @var array<string> States having the same class name in all versions */
	protected const array COMMON_STATES	= ['primary', 'success', 'danger', 'warning', 'info', 'link'];

	/**
	 *	@param		BsVersion		$version		Version to render classes for
	 *	@param		array<string>	$classes		Class names, each may contain several, separated by whitespace
	 *	@return		array<string>	Class names for given version, starting with "btn"
	 */
	public static function resolve( BsVersion $version, array $classes ): array
	{
		$state		= NULL;
		$outline	= FALSE;
		$size		= NULL;
		$block		= FALSE;
		$others		= [];
		foreach( $classes as $class ){
			$tokens	= preg_split( '/\s+/', trim( $class ), -1, PREG_SPLIT_NO_EMPTY );
			foreach( FALSE !== $tokens ? $tokens : [] as $token ){
				if( 1 === preg_match( '/^btn-outline-([a-z]+)$/', $token, $matches ) ){
					$state		= self::normalizeState( $matches[1] );
					$outline	= TRUE;
					continue;
				}
				$normalizedState	= 1 === preg_match( '/^btn-([a-z]+)$/', $token, $matches ) ? self::normalizeState( $matches[1] ) : NULL;
				if( NULL !== $normalizedState )
					$state	= $normalizedState;
				else if( in_array( $token, ['btn-mini', 'btn-xs'], TRUE ) )
					$size	= 'xs';
				else if( in_array( $token, ['btn-small', 'btn-sm'], TRUE ) )
					$size	= 'sm';
				else if( in_array( $token, ['btn-large', 'btn-lg'], TRUE ) )
					$size	= 'lg';
				else if( 'btn-block' === $token )
					$block	= TRUE;
				else if( 'btn' !== $token && !in_array( $token, $others, TRUE ) )
					$others[]	= $token;
			}
		}
		$list	= ['btn'];
		$list[]	= self::realizeState( $version, $state, $outline );
		$list[]	= NULL !== $size ? self::realizeSize( $version, $size ) : '';
		$list[]	= $block ? self::realizeBlock( $version ) : '';
		$list	= array_merge( $list, $others );
		return array_values( array_filter( $list, static fn( string $class ): bool => '' !== $class ) );
	}

	/**
	 *	@param		string		$state		State part of class name, like "default", "inverse" or "primary"
	 *	@return		string|NULL	Neutral state or NULL if not a state
	 */
	protected static function normalizeState( string $state ): ?string
	{
		return match( $state ){
			'default', 'secondary'	=> 'secondary',
			'inverse', 'dark'		=> 'dark',
			'light'					=> 'light',
			default					=> in_array( $state, self::COMMON_STATES, TRUE ) ? $state : NULL,
		};
	}

	protected static function realizeBlock( BsVersion $version ): string
	{
		return match( $version ){
			BsVersion::V2, BsVersion::V3, BsVersion::V4	=> 'btn-block',
			BsVersion::V5								=> 'w-100',				//  btn-block was removed
		};
	}

	protected static function realizeSize( BsVersion $version, string $size ): string
	{
		return match( $version ){
			BsVersion::V2	=> match( $size ){
				'xs'	=> 'btn-mini',
				'sm'	=> 'btn-small',
				default	=> 'btn-large',
			},
			BsVersion::V3	=> 'btn-'.$size,
			BsVersion::V4, BsVersion::V5	=> 'xs' === $size ? 'btn-sm' : 'btn-'.$size,	//  no extra small anymore
		};
	}

	protected static function realizeState( BsVersion $version, ?string $state, bool $outline ): string
	{
		$state	??= 'secondary';
		return match( $version ){
			BsVersion::V2	=> match( $state ){
				'secondary', 'light'	=> '',											//  plain btn is default
				'dark'					=> 'btn-inverse',
				default					=> 'btn-'.$state,
			},
			BsVersion::V3	=> match( $state ){
				'secondary', 'light', 'dark'	=> 'btn-default',							//  no light or dark buttons
				default							=> 'btn-'.$state,
			},
			BsVersion::V4, BsVersion::V5	=> $outline && 'link' !== $state ? 'btn-outline-'.$state : 'btn-'.$state,
		};
	}
}
