<?php
use Carbon\Carbon;
class Appointment extends Eloquent {

	protected $table = 'appointments';
	public $timestamps = true;

	public function patient()
	{
		return $this->belongsTo('Patient');
	}

	public function doctor()
	{
		return $this->belongsTo('Doctor');
	}

	public function mgsAppointments()
	{
		return $this->hasMany('MgsAppointment');
	}

	public function agendas()
	{
		return $this->belongsToMany('Agenda');
	}

	public static function StateCitasDay($user_id)
  {
        $pcitas   = DB::table('doctors')                
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'doctors.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('doctors.user_id', $user_id)
                      ->where('appointments.day', date('Y-m-d'))
                      ->select('appointments.state as state')
                      ->groupBy('appointments.id')->get();
        $citasaten= 0; 
        $con = 0;
        foreach($pcitas as $pcita)
        {
           if($pcita->state=='confirmed' or $pcita->state=='served' or $pcita->state=='delayed' or $pcita->state=='in-progress') 
               $con=$con+1;

           if($pcita->state=='served') 
               $citasaten=$citasaten+1;
        }
        if($pcitas)
           return $citasaten.'/'.$con;
        else
           return 'No hay Citas';
    }

    public static function retraso($user_id)
    {
        $pcitas = DB::table('doctors')                
                      ->join('doctor_patient', 'doctors.id', '=', 'doctor_patient.doctor_id')
                      ->join('patients', 'doctor_patient.patient_id', '=', 'patients.id')                      
                      ->join('users', 'doctors.user_id', '=', 'users.id') 
                      ->join('appointments', 'patients.id', '=', 'appointments.patient_id')
                      ->where('doctors.user_id', $user_id)
                      ->where('appointments.day', date('Y-m-d'))
                      ->groupBy('appointments.id')->get();
        $Ultimadif=0;
        $tiempo_en_segundos =0;
        foreach($pcitas as $pcita)
        {
            if($pcita->state=="delayed"){
             $start  = explode(' ',$pcita->start_date);
             $atrazo = explode(' ',$pcita->last_time);

             $startdate = explode('-',$start[0]);
             $starttime = explode(':',$start[1]);

             $atrazodate = explode('-',$atrazo[0]);
             $atrazotime = explode(':',$atrazo[1]);

             $dtstart = Carbon::create($startdate[0], $startdate[1], $startdate[2], $starttime[0], $starttime[1], $starttime[2]);
             $dtatrazo = Carbon::create($atrazodate[0], $atrazodate[1], $atrazodate[2], $atrazotime[0], $atrazotime[1], $atrazotime[2]);
             
             $tiempo_en_segundos = $tiempo_en_segundos+$dtatrazo->diffInSeconds($dtstart);
             $Ultima = $Ultimadif;
             $Ultimadif = $dtatrazo->diffInSeconds($dtstart);
             }
        }
        
        if(isset($tiempo_en_segundos)){
          $t = $tiempo_en_segundos-$Ultimadif;
          $horas = floor($tiempo_en_segundos / 3600);
          $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);

          if($horas!=0){
            $timeatrazo = $horas . 'Horas y ' . $minutos.' minutos Atrazado';
          }elseif($minutos!=0){
            $timeatrazo = $minutos.' minutos Atrazado';
          }else
              $timeatrazo='En el horario.';

          if($t!=0){
          $horas2 = floor($t / 3600);
          $minutos2 = floor(($t - ($horas2 * 3600)) / 60);
          if($horas2!=0){
            $timeatrazo2 = $horas2 . 'Horas y ' . $minutos2.' minutos Atrazado';
          }else{
            $timeatrazo2 = $minutos2.' minutos Atrazado';
          }
          
          }else
              $timeatrazo2='En el horario.';

           return array('primero'=>$timeatrazo, 'segundo'=>$timeatrazo2);
         }else
           return array('primero'=>'En el horario.', 'segundo'=>'En el horario.');
    }

}