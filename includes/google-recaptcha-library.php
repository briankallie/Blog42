<?php
function g_recaptcha_get_form_control($g_recaptcha_theme = 'light') {
    global $g_recaptcha_key_site;
    return '<div class="g-recaptcha" data-theme="' . $g_recaptcha_theme . '" data-sitekey="' . $g_recaptcha_key_site . '"></div>';
}

function g_recaptcha_request() {
    global $g_recaptcha_key_secret;

    set_error_handler(
    create_function(
        '$severity, $message, $file, $line',
        'throw new ErrorException($message, $severity, $severity, $file, $line);'
    )
);
    try {

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret'   => $g_recaptcha_key_secret,
                 'response' => $_POST['g-recaptcha-response'],
                 'remoteip' => $_SERVER['REMOTE_ADDR']];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data) 
            ]
        ];

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        return json_decode($result);
    }
    catch (Exception $e) {

        // return $e->getMessage();
        return json_decode('{"success": "fail"}');
    }
    restore_error_handler();
}