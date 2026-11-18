<?php
declare(strict_types=1);

namespace CeusMedia\Bootstrap\Base\Dialect;

/**
 *	Dialect of Bootstrap 2.
 *	@category		Library
 *	@package		CeusMedia_Bootstrap
 */
class V2 extends AbstractDialect
{
	protected const array CLASSES	= [
		'btn-close'				=> 'close',
		'd-none'				=> 'hide',
		'dropdown-menu-end'		=> 'pull-right',
		'float-end'				=> 'pull-right',
		'float-start'			=> 'pull-left',
	];
}
