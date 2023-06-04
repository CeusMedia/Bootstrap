<?php
use CeusMedia\Bootstrap\Alert;

class AlertTest extends PHPUnit\Framework\TestCase
{
	public function testConstruct()
	{
		$label	= 'Alert Message';
		$class	= Alert::CLASS_SUCCESS;
		$alert	= new UnprotectedAlert( $label, $class );

		$this->assertEquals( $label, $alert->getContent() );
		$this->assertEquals( explode( ' ', $class ), $alert->getClasses() );

		$class	= Alert::CLASS_ERROR;
		$alert	= new UnprotectedAlert( $label, $class );
		$this->assertEquals( explode( ' ', $class ), $alert->getClasses() );
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

		$expected	= '<div class="alert alert-success" role="alert"><rector.php href="#" class="close" data-dismiss="alert">&times;</rector.php>Alert Message</div>';
		$this->assertEquals( $expected, $alert->render() );

		$alert->useDismiss( FALSE );

		$expected	= '<div class="alert alert-success" role="alert">Alert Message</div>';
		$this->assertEquals( $expected, $alert->render() );
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
