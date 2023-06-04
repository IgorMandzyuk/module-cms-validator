<?php

namespace Im\CMSValidator\Model\Validators;

class HTML
{
    const IGNORE_CODES = [37,517];

    /**
     * @param $html
     * @return array|void
     */
    public function execute($html = ''){
        $errorCodes = [];
        if (!strlen($html)){
            return $errorCodes;
        }
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $isValid = $dom->validate();
        if ($isValid) {
           return $errorCodes;
        } else {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                if (!in_array($error->code, self::IGNORE_CODES)){
                    $errorCodes[] = 'Error: ' . $error->message . 'in line ' . $error->line;
                }
            }
        }
        libxml_clear_errors();
        return $errorCodes;
    }
}