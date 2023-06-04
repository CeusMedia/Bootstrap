<?php
namespace CeusMedia\BootstrapTest;

use CeusMedia\Bootstrap\Alert;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
	public function testConstruct()
	{
		$label	= 'Alert Message';
		$class	= Alert::CLASS_SUCCESS;
		$alert	= new UnprotectedAlert( $label, $class );

		self::assertEquals( $label, $alert->getContent() );
		self::assertEquals( explode( ' ', $class ), $alert->getClasses() );

		$class	= Alert::CLASS_ERROR;
		$alert	= new UnprotectedAlert( $label, $class );
		self::assertEquals( explode( ' ', $class ), $alert->getClasses() );
	}

	/**
	 *	@covers		::render
	 */
	public function testUseDismiss()
	{
		$label	= 'Alert Message';
		$class	= Alert::CLASS_SUCCESS;
		$alert	= new UnprotectedAlert( $label, $class );
		$alert->useDismiss( TRUE );

		$expected	= '<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a>Alert Message</div>';
		self::assertEquals( $expected, $alert->render() );

		$alert->useDismiss( FALSE );

		$expected	= '<div class="alert alert-success" role="alert">Alert Message</div>';
		self::assertEquals( $expected, $alert->render() );
	}
}

class UnprotectedAlert extends Alert
{
	public function getClasses(): array
	{
		return $this->classes;
	}

	public function getContent(): string
	{
		return $this->content;
	}
}
