<?php

return [
    'required'   => ':attribute ველი აუცილებელია.',
    'email'      => ':attribute უნდა იყოს ვალიდური ელ.ფოსტა.',
    'unique'     => ':attribute უკვე არსებობს.',
    'min'        => ':attribute უნდა შეიცავდეს მინიმუმ :min სიმბოლოს.',
    'max'        => ':attribute არ უნდა აღემატებოდეს :max სიმბოლოს.',
    'confirmed'  => ':attribute ვერ დადასტურდა.',
    'regex'      => ':attribute ფორმატი არასწორია. დაშვებულია მხოლოდ პატარა ასოები და ციფრები.',
    'boolean'    => ':attribute ველი უნდა იყოს true ან false.',
    'exists'     => 'არჩეული :attribute არასწორია ან არ არსებობს.',
    'attributes' => [
        'name'     => 'მომხმარებლის სახელი',
        'email'    => 'ელ.ფოსტა',
        'token'    => 'აღდგენის ტოკენი',
        'password' => 'პაროლი',
        'remember' => 'დამახსოვრება',
    ],
];
