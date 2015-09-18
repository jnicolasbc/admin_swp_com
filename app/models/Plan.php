<?php

class Plan extends Eloquent {

	protected $table = 'plan';
	public $timestamps = true;

	public static function VeryPayment()
    {
        if($user = Sentry::getUser()){
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

     
    public function scopeExtractID($query, $type)
    {
        $a= $query->whereCode($type)->first();        
        if(!is_null($a)){
            return $a->id;
        }else{
            return App::abort(404);
        }
    }

    public function scopeIsClinic($query, $type)
    {
        $a= $query->whereCode($type)->first()->code;        
        list($type,$op) = explode('-', $a);
        if($type=='c'){
            return 'c';
        }else{
            return 'd';
        }
    }
}