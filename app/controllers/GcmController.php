<?php

class GcmController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	 function __construct() {
         
    }
 
     
    public static function send_notification($registatoin_ids, $message) {                
        define("GOOGLE_API_KEY", "AIzaSyB-RY0KfbkgBDYtBi8U-d90ODein1a2d4M");
        // variable post http://developer.android.com/google/gcm/http.html#auth
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
 
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // abriendo la conexion
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Deshabilitamos soporte de certificado SSL temporalmente
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // ejecutamos el post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Cerramos la conexion
        curl_close($ch);
        echo $result;
    }

    public function send()
    {
        $id=User::find(Input::get("id"))->gcm_id;
        $regId=array($id);
        $message =Input::get("message");
        $type =Input::get("type");
        $mensaje = array(
        'tipo'          => $type,
        'message'       => $message, //mensaje a enviar
        'title'         => 'Agenda Medic',// Titulo de la notificación
        'msgcnt'        => '1',/*Número que sirve para informar cantidad de mensajes o eventos.
                                Se muestra en la parte derecha de la notificación
                                (En Android 2.3.6 no me lo muestra, me imagino que debe depender de la versión)*/
        //'soundname'   => 'sonido.wav',//Sonido a reproducir *debe estar en la carpeta raíz
        'collapseKey' => 'Demo',
            /*Texto que sirve para colapsar las notificaciones cuando el dispositivo esta offline.
            Esto detecta si el dispositivo estaba sin acceso a red,
            de tal manera que una vez este en línea no le lleguen un montón
            de notificaciones al tiempo; solo llegará la última de cada notificación 
            que tenga el mismo collapseKey*/
        'timeToLive' => 3000,/* Tiempo en segundos para mantener la notificación en GMC 
                                y volver a intentar el envío de esta. 
                                Default 4 semanas (2,419,200 segundos) si no es especificado.*/
        'delayWhileIdle' => true, //Default is false
        //Mas opciones en http://developer.android.com/google/gcm/server.html#params
        );

 
        $result = $this->send_notification($regId, $mensaje);
 
        echo $result;
    }
    

}