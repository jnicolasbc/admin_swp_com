<?php

class CustomDay extends Eloquent {

	protected $table = 'custom_days';
	public $timestamps = true;

    public static function  eliminarespacios($time)
    {
       if($time!=""){
       $trimmed = explode( " ", $time);
       return $trimmed[0].$trimmed[1].$trimmed[2].":00";
       }elseif($time==""){
          return "00:00:00";
       }
    }

    public static function  addespacios($time)
    {
      if($time!=""){
      $trimmed = explode( ":", $time);
      return $trimmed[0]." : ".$trimmed[1];
      }else{
         return "00 : 00";
      }
    }

    public static function  time($date)
    {
      if($date!=""){
      $trimmed = explode( " ", $date);
      $trimtime = explode(':', $trimmed[1]);
      
      return $trimtime[0]." : ".$trimtime[1];
      }else{
         return "00 : 00";
      }
    }
}