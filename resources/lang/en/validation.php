<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'min' => 'The :attribute must be at least :min characters.',
    'max' => 'The :attribute may not be greater than :max characters.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'regex' => 'The :attribute format is invalid. Only lowercase letters and numbers are allowed.',
    'boolean' => 'The :attribute field must be true or false.',
    'exists' => 'The selected :attribute is invalid or does not exist.',
    'attributes' => [
        'name' => 'username',
        'email' => 'email',
        'token' => 'reset token',
        'password' => 'password',
        'remember' => 'remember me',
    ],
];
