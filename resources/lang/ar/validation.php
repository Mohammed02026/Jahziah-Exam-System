<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون :attribute بريدًا إلكترونيًا صحيحًا.',
    'min' => [
        'string' => 'يجب ألا يقل :attribute عن :min أحرف.',
        'numeric' => 'يجب ألا يقل :attribute عن :min.',
    ],
    'max' => [
        'string' => 'يجب ألا يزيد :attribute عن :max حرفًا.',
        'numeric' => 'يجب ألا يزيد :attribute عن :max.',
    ],
    'confirmed' => 'تأكيد :attribute غير مطابق.',
    'unique' => ':attribute مستخدم مسبقًا.',
    'exists' => ':attribute غير صحيح.',
    'in' => ':attribute غير صحيح.',

    'attributes' => [
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'name' => 'الاسم',
        'role' => 'الدور',
    ],
];
