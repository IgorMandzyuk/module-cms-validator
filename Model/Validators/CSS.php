<?php

namespace Im\CMSValidator\Model\Validators;

class CSS
{
    public function execute($css = '') {
        $data = http_build_query([
            'css' => '<style>body {
  background-image: url("barn.jpg");
  background-repeat: no-repeat;
  background-position: right top;
  background-attachment: fixed;
}</style>'
        ]);

        $url = 'https://www.w3schools.com/webservices/cssvalidate.php';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            // Request failed
            echo "CSS validation request failed.\n";
            return false;
        }

        var_dump($response);

        $xml = simplexml_load_string($response);

        if ($xml->valid == 'true') {
            echo "CSS is valid.\n";
           // return true;
        } else {
            // Get the error messages
            $errors = $xml->errorlist->error;

            echo "CSS Validation Errors:\n";
            foreach ($errors as $error) {
                echo $error->message . "\n";
            }
die();
         //   return false;
        }
    }

}