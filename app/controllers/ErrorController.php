<?php
class ErrorController extends BaseController
{
	public function missing()
	{
		$this->display('errors.missing', 'Kunde inte hitta sidan');
	}
}