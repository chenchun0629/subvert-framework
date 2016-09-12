<?php

namespace Com\Traits;

use Validator;

trait ValidatorTrait
{
    public function validate(array $data, array $rules, array $messages = [])
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            return [
                'is_valid'  => false,
                'errors'    => $validator->errors(),
            ];
        }

        return [
            'is_valid'  => true,
            'errors'    => []
        ];
    }

}