<?php
declare(strict_types=1);

/**
 *	Resolves version prefixed class names in demo markup.
 *	Classes like "bs3-col-md-3" are kept (without prefix) for Bootstrap 3 and removed for other versions.
 *	Classes without version prefix are kept as they are.
 */
class VersionProcessor
{
	public static function process( string $content, string $version ): string
	{
		$majorVersion	= self::getMajorVersion( $version );
		$result	= preg_replace_callback( '/ class="([^"]*)"/', static function( array $matches ) use ( $majorVersion ): string {
			$classes	= [];
			$tokens		= preg_split( '/\s+/', trim( $matches[1] ), -1, PREG_SPLIT_NO_EMPTY );
			foreach( FALSE !== $tokens ? $tokens : [] as $class ){
				if( 1 !== preg_match( '/^bs([0-9]+)-(.+)$/', $class, $parts ) )
					$classes[]	= $class;														//  not version specific
				else if( (int) $parts[1] === $majorVersion )
					$classes[]	= $parts[2];													//  for this version: remove prefix
			}
			return [] !== $classes ? ' class="'.join( ' ', $classes ).'"' : '';
		}, $content );
		return $result ?? $content;
	}

	protected static function getMajorVersion( string $version ): int
	{
		$versionParts	= explode( '.', $version );
		return (int) array_shift( $versionParts );
	}
}
