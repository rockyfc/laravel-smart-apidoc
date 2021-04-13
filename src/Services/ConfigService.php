<?php

namespace Smart\ApiDoc\Services;

class ConfigService
{
    public static function key()
    {
        return 'smart-doc';
    }

    public static function config()
    {
        return config(self::key());
    }

    public static function enabled()
    {
        return config(self::key() . '.enabled');
    }

    public static function domain()
    {
        return config(self::key() . '.domain');
    }

    public static function filters()
    {
        return config(self::key() . '.filters');
    }

    public static function prefix()
    {
        return config(self::key() . '.prefix');
    }

    public static function query()
    {
        return config(self::key() . '.query');
    }

    public static function queryFieldsName()
    {
        return config(self::key() . '.query.fieldsName');
    }

    public static function queryFilterName()
    {
        return config(self::key() . '.query.filterName');
    }

    public static function queryRelationName()
    {
        return config(self::key() . '.query.relationName');
    }

    public static function querySortName()
    {
        return config(self::key() . '.query.sortName');
    }

    public static function mdFiles()
    {
        return config(self::key() . '.mdFiles');
    }

    public static function commonParams()
    {
        return config(self::key() . '.commonParams');
    }

    public static function middleware()
    {
        return config(self::key() . '.middleware');
    }

    public static function routeFormat()
    {
        return config(self::key() . '.route_format');
    }


}
