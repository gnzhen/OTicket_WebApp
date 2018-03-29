<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\FCMToken;
class UserFcmTokenController extends Controller
{
    /**
     * 
     * @var user
     * @var FCMToken
     */
    protected $user;
    protected $fcmToken;

    /**
     * Constructor
     * 
     * @param 
     */
    public function __construct(User $user, FCMToken $fcmToken)
    {
        $this->user = $user;  
        $this->fcmToken = $fcmToken;      
    }

     /**
     * Functionality to send notification.
     * 
    */

    public function sendNotification(Request $request)
    {

        $tokens = [];
        $apns_ids = [];
        $responseData = [];
		$data= $request->all();
		$users= $data['user_ids']; 
        
	// for Android
	
        if ($FCMTokenData = $this->fcmToken->whereIn('user_id',$users)->where('token','!=',null)->select('token')->get()) 
        {
            foreach ($FCMTokenData as $key => $value) 
            {
                $tokens[] = $value->token;
              
            }

            define('SERVER_KEY', 'AAAAdl68zGM:APA91bFSosS6qaPTAT2PpnyBKxbZSwrJHbp2UKiWYTvUUtyWPGePaeyQdlNLPajb1TjAqvRPfDL8QRTBwEEMoHmAbB_g2m4rrCa7vDDfv8orZXdM6HvURo9WYUBFepUMUp32U2tYJw_T' );

            $msg = array
                  (
                'body'  => 'This is body of notification',
                'title' => 'Notification',
                'subtitle' => 'This is a subtitle',
                  );

            $fields = array
                    (
                        'registration_ids'  => $tokens,
                        'notification'  => $msg
                    );
            
            
            $headers = array
                    (
                        'Authorization: key=' . 'AAAAdl68zGM:APA91bFSosS6qaPTAT2PpnyBKxbZSwrJHbp2UKiWYTvUUtyWPGePaeyQdlNLPajb1TjAqvRPfDL8QRTBwEEMoHmAbB_g2m4rrCa7vDDfv8orZXdM6HvURo9WYUBFepUMUp32U2tYJw_T',
                        'Content-Type: application/json'
                    );
   
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );

            if ($result === FALSE) 
            {
                die('FCM Send Error: ' . curl_error($ch));
            }
            $result = json_decode($result,true);

            $responseData['android'] =[
                       "result" =>$result
                    ];
          
            curl_close( $ch );
           
        }
    
         return $responseData;

     }

}