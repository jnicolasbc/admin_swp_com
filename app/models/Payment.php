<?php
use Carbon\Carbon;
class Payment extends Eloquent {

	protected $table = 'payment';
	public $timestamps = true;

	public static function VeryPayment()
    {
        if($user = Sentry::getUser()){
            $doctors = Doctor::where('user_id',$user->id)->first();
            if($doctors){
                if($doctors->clinic_id > 0)
                    return true;
            }

    		$dt = Carbon::now();
            $payment = Payment::where('user_id', $user->id)->first();
            $plan = Plan::find($payment->plan_id);
            $create =  Carbon::parse($payment->created_at);
            if($create->addDays($plan->time) > $dt)
               return true;
            else
               return false;
        }
    }

    public static function VeryTypeDoctor()
    {
        if($user = Sentry::getUser()){
            $doctors = Doctor::where('user_id',$user->id)->first();
            if($doctors){
                if($doctors->clinic_id > 0)
                    return true;
            }
        }
    }
}