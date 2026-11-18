<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Dialect;

/**
 *	Dialect of Bootstrap 5.
 *	Neutral names are the names of Bootstrap 5, so classes are kept as they are.
 *	A future dialect for Bootstrap 6 can extend this class and override differences only.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class V5 extends AbstractDialect
{
	protected const string DATA_PREFIX	= 'bs-';
}
