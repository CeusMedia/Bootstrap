<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Dialect;

/**
 *	Translates neutral class names and data attributes to a specific Bootstrap version.
 *	Neutral names are the names of Bootstrap 5.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
interface Dialect
{
	/**
	 *	Translates neutral class names into class names of this version.
	 *	Each argument may contain several class names, separated by whitespace.
	 *	Unknown class names are kept as they are.
	 *	@param		string		...$classes
	 *	@return		string		Space separated list of translated class names
	 */
	public function classes( string ...$classes ): string;

	/**
	 *	Returns data key for setData(), like "toggle" or "bs-toggle".
	 *	@param		string		$name		Neutral name, like "toggle" or "dismiss"
	 *	@return		string
	 */
	public function dataKey( string $name ): string;

	/**
	 *	Returns full data attribute name, like "data-toggle" or "data-bs-toggle".
	 *	@param		string		$name		Neutral name, like "toggle" or "dismiss"
	 *	@return		string
	 */
	public function data( string $name ): string;
}
