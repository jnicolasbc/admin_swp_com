<?php
use \Api\V1\Helpers as H;
use Carbon\Carbon;
class PagesController extends \BaseController {


	public function getIndex()
	{
		return View::make('front.pages.index');
	}

}