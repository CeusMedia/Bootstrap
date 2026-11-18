<?php
declare(strict_types=1);

/**
 *	Translates alert class names between Bootstrap versions.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2012-2026 {@link https://ceusmedia.de/ Ceus Media}
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Bootstrap
 */
namespace CeusMedia\Bootstrap\Base;

/**
 *	Translates alert class names between Bootstrap versions.
 *
 *	Reads state (color) from class names of any version, like "alert-error" (2) or "alert-danger" (3+),
 *	and renders class names of given version.
 *	Without state, Bootstrap 2 shows a warning, so later versions get class "alert-warning".
 *	States not existing in older versions are shown as info.
 *	Other class names are kept as they are.
 *
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class AlertClassResolver
{
	/**
	 *	@param		BsVersion		$version		Version to render classes for
	 *	@param		array<string>	$classes		Class names, each may contain several, separated by whitespace
	 *	@param		boolean			$dismissible	Flag: add classes for dismissible alert
	 *	@return		list<string>	Class names for given version, starting with "alert"
	 */
	public static function resolve( BsVersion $version, array $classes, bool $dismissible = FALSE ): array
	{
		$parser	= new StateClassParser( $classes, ['alert'] );
		$list	= ['alert', self::realizeState( $version, $parser->state )];
		if( $dismissible )
			$list	= array_merge( $list, match( $version ){
				BsVersion::V2	=> [],
				BsVersion::V3	=> ['alert-dismissible'],
				BsVersion::V4, BsVersion::V5	=> ['alert-dismissible', 'fade', 'show'],
			} );
		$list	= array_merge( $list, $parser->others );
		return array_values( array_unique( array_filter( $list, static fn( string $class ): bool => '' !== $class ) ) );
	}

	/**
	 *	@param		BsVersion		$version
	 *	@param		string|NULL		$state		Neutral state or NULL
	 *	@return		string			Class name of state, empty if not needed
	 */
	protected static function realizeState( BsVersion $version, ?string $state ): string
	{
		return match( $version ){
			BsVersion::V2	=> match( $state ){
				NULL, 'warning'						=> '',							//  default alert is a warning
				'danger'							=> 'alert-error',
				'success', 'info'					=> 'alert-'.$state,
				default								=> 'alert-info',					//  primary, secondary, light, dark
			},
			BsVersion::V3	=> match( $state ){
				NULL								=> 'alert-warning',
				'success', 'info', 'warning', 'danger'	=> 'alert-'.$state,
				default								=> 'alert-info',					//  primary, secondary, light, dark
			},
			BsVersion::V4, BsVersion::V5	=> 'alert-'.( $state ?? 'warning' ),
		};
	}
}
