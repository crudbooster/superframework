<?php

namespace system\Helpers;


class FCM
{

    private $ios_reg_id = [];
    private $android_reg_id = [];
    private $data = [];
    private $title;
    private $message;

    /**
     * @param $title
     */
    public function title($title)
    {
        $this->title = $title;
    }

    /**
     * @param $message
     */
    public function message($message)
    {
        $this->message = $message;
    }

    /**
     * @param $data
     */
    public function data($data)
    {
        $this->data = $data;
    }

    /**
     * @param $reg_id
     */
    public function addIosToken($reg_id)
    {
        $this->ios_reg_id[] = $reg_id;
    }

    /**
     * @param $reg_id
     */
    public function addAndroidToken($reg_id)
    {
        $this->android_reg_id[] = $reg_id;
    }

    public function send() {
        $data['title'] = $this->title;
        $data['message'] = $this->message;
        if(count($this->android_reg_id) > 0) {
            $fields = [
                'registration_ids' => $this->android_reg_id,
                'data' => $data,
                'content_available' => true,
                'priority' => 'high',
            ];
            $response = $this->curlFCM($fields);
            logging($response);
        }

        if(count($this->ios_reg_id) > 0) {
            $fields = [
                'registration_ids' => $this->ios_reg_id,
                'data' => $data,
                'content_available' => true,
                'notification' => [
                    'sound' => 'default',
                    'badge' => 0,
                    'title' => trim(strip_tags($this->title)),
                    'body' => trim(strip_tags($this->message)),
                ],
                'priority' => 'high',
            ];
            $response = $this->curlFCM($fields);
            logging($response);
        }
    }

    private function curlFCM($fields)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization:key='.config('google_fcm_server_key'),
            'Content-Type:application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}