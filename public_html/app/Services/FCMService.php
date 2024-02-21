<?php
    namespace App\Services;
    class FCMService{

        public static function spark_send_fcm($notifiable, $title = '', $body = '', $notification_content = []){
            $to = $notifiable->devices()->pluck('device_token')->toArray();
            $notifiable->notifications()->create($notification_content);
            foreach ($to as $device) {
                self::spark_send_single_fcm($device, $title, $body, $notification_content);
            }
        }

        public static function spark_send_single_fcm($to = '', $title = '', $body = '', $notification_content = []){
            
            $customData = array_merge($notification_content, ["priority" => "high", "visibility" => "public", "importance" => "max"]);
            $device_token = $to;
            $data = [
                "to" => $device_token,
                "notification" => array_merge([
                    "body" => $body,
                    "title" => $title,
                    'badge' => 1,
                ],
                    $customData
                ),
                "data" => $customData
            ];

            $options = [
                'http' => [
                    'method' => 'POST',
                    'content' => json_encode($data),
                    'header' => "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n" .
                        "Authorization:key=" . config('fcm.http.server_key')
                ]
            ];
            $context = stream_context_create($options);
            $result = file_get_contents(config('fcm.http.server_send_url'), false, $context);
            
            return json_decode($result);
        }
    }
