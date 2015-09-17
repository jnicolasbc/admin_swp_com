<?php

class ClinicPaymentController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getClinicHistory()
	{
		if(Payment::VeryPayment()==false)
           return View::make('clinic.payment.renews-payment');
       
		$user    = Sentry::getUser();
		$payments = Userpayment::where('user_id', $user->id)->where('paid', 1)->get();
		return View::make('clinic.Payment.History')->with('payments', $payments);
	}



}