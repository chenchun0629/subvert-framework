<?php

namespace Com\Bootstrap\Validation;

use Subvert\Framework\Contract\Validatable;

abstract class SignValidation implements Validatable
{

    abstract public function getKey();

    public function validate($data)
    {
        $saltKey = $this->getKey();
        $encrypt = $this->encrypt($data['call']['api'], $data['call']['data'], $data['call']['api_version'], $saltKey);

        if ($data['body']['sign'] == $encrypt) {
            return [
                'result' => true,
                'encrypt' => $encrypt,
            ];
        }

        return [
            'result' => false,
            'encrypt' => $encrypt,
        ];
    }

    protected function encrypt($api, $data, $apiVersion, $saltKey)
    {
        $data = is_array($data) ? json_encode($data) : $data;
        return md5($api . $apiVersion . $data . $saltKey);
    }

}