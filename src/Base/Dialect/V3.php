<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Dialect;

/**
 *	Dialect of Bootstrap 3.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class V3 extends AbstractDialect
{
	protected const array CLASSES	= [
		'btn-close'				=> 'close',
		'd-none'				=> 'hidden',
		'dropdown-menu-end'		=> 'dropdown-menu-right',
		'float-end'				=> 'pull-right',
		'float-start'			=> 'pull-left',
	];
}
