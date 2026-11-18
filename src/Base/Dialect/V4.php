<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Dialect;

/**
 *	Dialect of Bootstrap 4.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class V4 extends AbstractDialect
{
	protected const array CLASSES	= [
		'btn-close'				=> 'close',
		'dropdown-menu-end'		=> 'dropdown-menu-right',
		'float-end'				=> 'float-right',
		'float-start'			=> 'float-left',
		'me-auto'				=> 'mr-auto',
		'ms-auto'				=> 'ml-auto',
	];
}
