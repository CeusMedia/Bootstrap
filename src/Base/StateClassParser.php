<?php
declare(strict_types=1);

/**
 *	Parses state (color) from class names of any Bootstrap version.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

/**
 *	Parses state (color) from class names of any Bootstrap version.
 *
 *	Class names like "alert-error" (2), "label-important" (2), "badge-inverse" (2),
 *	"label-default" (3) or "bg-danger" (5) are normalized to the neutral states of Bootstrap 4+:
 *	primary, secondary, success, info, warning, danger, light, dark.
 *	Components (or their class resolvers, like AlertClassResolver) decide, how to render these states for their version.
 *
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class StateClassParser
{
	public const array STATES	= ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark'];

	/** @var string|NULL Normalized state or NULL if none given */
	public ?string $state		= NULL;

	/** @var array<string> Other class names, which are no state */
	public array $others		= [];

	/**
	 *	@param		array<string>	$classes		Class names, each may contain several, separated by whitespace
	 *	@param		array<string>	$prefixes		Prefixes of state classes, like "alert" or "badge"
	 */
	public function __construct( array $classes, array $prefixes )
	{
		$pattern	= '/^('.join( '|', array_map( static fn( string $prefix ): string => preg_quote( $prefix, '/' ), $prefixes ) ).')-([a-z]+)$/';
		foreach( $classes as $class ){
			$tokens	= preg_split( '/\s+/', trim( $class ), -1, PREG_SPLIT_NO_EMPTY );
			foreach( FALSE !== $tokens ? $tokens : [] as $token ){
				$state	= 1 === preg_match( $pattern, $token, $matches ) ? self::normalize( $matches[2] ) : NULL;
				if( NULL !== $state )
					$this->state	= $state;
				else if( !in_array( $token, $this->others, TRUE ) )
					$this->others[]	= $token;
			}
		}
	}

	/**
	 *	Returns state as neutral name, or NULL if not a state.
	 *	@param		string		$name		Like "error", "important", "inverse", "default" or "danger"
	 *	@return		string|NULL
	 */
	public static function normalize( string $name ): ?string
	{
		return match( $name ){
			'error', 'important'	=> 'danger',
			'inverse'				=> 'dark',
			'default'				=> 'secondary',
			default					=> in_array( $name, self::STATES, TRUE ) ? $name : NULL,
		};
	}
}
