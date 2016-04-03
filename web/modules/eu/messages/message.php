<?php

$arr = 
[
    'sourcePath' => __DIR__ . '/../',
    'messagePath' => __DIR__,
    'languages' => [
        'ca',
        'da',
        'de']];

file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", print_r(array(" 1arr " => $arr), true), FILE_APPEND | LOCK_EX);

return [
    'sourcePath' => __DIR__ . '/../',
    'messagePath' => __DIR__,
    'languages' => [
        'ca',
        'da',
        'de',
        'es',
        'fa-IR',
        'fi',
        'fr',
        'hr',
        'hu',
        'it',
        'kz',
        'lt',
        'nl',
        'pl',
        'pt-BR',
        'pt-PT',
        'ro',
        'ru',
        'th',
        'tr_TR',
        'uk',
        'vi',
        'zh-CN',
    ],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/tests',
        '/vendor',
    ],
    'format' => 'php',
];
