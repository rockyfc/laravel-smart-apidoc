<?php

return [
    'domain' => env('DOC_DOMAIN', null),

    'enabled' => env('DOC_ENABLED', true),

    'filters' => env('DOC_FILTERS', 'api'),

    'prefix' => env('DOC_PREFIX', 'smart-doc'),

    'query' => [
        'fieldsName' => env('DOC_QUERY_FIELDS_NAME', 'fields'),
        'filterName' => env('DOC_QUERY_FILTER_NAME', 'filter'),
        'relationName' => env('DOC_QUERY_RELATION_NAME', 'relations'),
        'sortName' => env('DOC_QUERY_SORT_NAME', 'sort'),
    ],

    'mdFiles' => [
        'system' => true, //smart-doc系统内置的md文件
        'custom' => [
            //用户自定义的md文件地址。
        ],
    ],

    //接口公共参数
    'commonParams' => [
        'app_id' => [
            'required' => true,
            'isRelation' => false,
            'type' => 'string',
            'default' => null,
            'comment' => 'appId',
            'options' => [],
        ],
        'time' => [
            'required' => true,
            'isRelation' => false,
            'type' => 'string',
            'default' => null,
            'comment' => '东八区时间戳，与服务器时间间隔不能超过两分钟',
            'options' => [],
        ],
        'format' => [
            'required' => false,
            'isRelation' => false,
            'type' => 'string',
            'default' => 'json',
            'comment' => '返回值格式，目前仅支持json',
            'options' => ['json'],
        ],
        'sign' => [
            'required' => true,
            'isRelation' => false,
            'type' => 'string',
            'default' => null,
            'comment' => '接口签名',
            'options' => [],
        ],
        'sign_type' => [
            'required' => false,
            'isRelation' => false,
            'type' => 'string',
            'default' => 'md5',
            'comment' => '接口签名方式，目前仅支持md5',
            'options' => [],
        ],
    ],

    'middleware' => [
        'web',
    ],
];