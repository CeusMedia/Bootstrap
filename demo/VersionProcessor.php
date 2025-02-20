<?php
declare(strict_types=1);

class VersionProcessor
{
	public static function process( string $content, string $version ): string
	{
		$majorVersion	= self::getMajorVersion( $version );
		$cssPrefix		= 'bs'.$majorVersion.'-';
		while( 1 === preg_match( '/ class="[^"]*'.$cssPrefix.'/', $content ) ){
			$pattern	= '/(class=")([^"]*)?('.$cssPrefix.')([^ "]+)([^"]*)(")/';
			/** @var string $content */
			$content	= preg_replace( $pattern, '\\1\\2\\4\\5\\6', $content );
		}
		$otherVersions	= array_diff( [2, 3, 4, 5], [$majorVersion] );
		foreach( $otherVersions as $version ){
			$pattern	= '/(class=")([^"]*)(bs'.$version.'-[^ "]+)([^"]*)(")/';
			/** @var string $content */
			$content	= preg_replace( $pattern, '\\1\\2\\4\\5', $content );
		}
		/** @var string $content */
		$content	= preg_replace( '/(class=")\s*([^ ]*)\s*(")/', '\\1\\2\\3', $content );
		/** @var string $content */
		$content	= preg_replace( '/ class=""/', '', $content );
		return $content;
	}

	protected static function getMajorVersion( string $version ): int
	{
		$versionParts	= explode( '.', $version );
		return (int) array_shift( $versionParts );
	}
}
