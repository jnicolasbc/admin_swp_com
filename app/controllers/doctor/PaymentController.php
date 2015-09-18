<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaymentController extends BaseController
{
    private $_api_context;

    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getDoctorHistory()
	{       
		$id = Doctor::doctorLogin();
        $doctor =Doctor::where('id',$id)->first();
		$payments = Userpayment::where('user_id', $doctor->user_id)->where('paid', 1)->get();
		return View::make('clinic.doctor.Payment.History')->with('payments', $payments);
	}



}