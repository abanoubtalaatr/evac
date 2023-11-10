<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAA5B3cnVg:APA91bGmt3c6LLqOuoFwRv8SpISk6x1oCrtmt5XDBZGcYuOAAseAc7NfRDsXpLTI1EMZj9RXvMW8pAx_9BRLU5tMKx7FTh8WPK4OaqtkphAF-XkRlUv54MW11282RmC9uJ5-P-DaTmFc'),
        'sender_id' => env('FCM_SENDER_ID', '979753540952'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
