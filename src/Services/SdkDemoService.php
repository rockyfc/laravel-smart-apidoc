<?php

namespace Smart\ApiDoc\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Smart\Common\Comment\FieldObject;
use Smart\Common\Services\SdkRestNameService;

class SdkDemoService
{
    /**
     * @var Route
     */
    protected $route;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var FieldObject[]
     */
    protected $queryParams = [];

    /**
     * @var FieldObject[]
     */
    protected $bodyParams = [];

    /**
     * SdkDemoService constructor.
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->files = new Filesystem();
    }

    public function getDemoContent()
    {
        $stub = $this->files->get($this->getStub());

        $this->replaceRestClass($stub);
        $this->replaceSetHeaderParams($stub);
        $this->replaceSetQueryParams($stub);
        $this->replaceSetBodyParams($stub);

        return $stub;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param FieldObject[] $queryParams
     * @return $this
     */
    public function setQueryParams(array $queryParams)
    {
        $this->queryParams = $queryParams;

        return $this;
    }

    /**
     * @param FieldObject[] $bodyParams
     * @return $this
     */
    public function setBodyParams(array $bodyParams)
    {
        $this->bodyParams = $bodyParams;

        return $this;
    }

    protected function restName()
    {
        return str_replace(
            '\\\\',
            '\\',
            (new SdkRestNameService($this->route))->generateApiName()
        );
    }

    protected function replaceRestClass(&$stub)
    {
        $stub = str_replace(
            ['{{ rest_class }}', '{{rest_class}}'],
            $this->restName(),
            $stub
        );

        return $this;
    }

    protected function replaceSetHeaderParams(&$stub)
    {
        $replace = '';
        foreach ($this->headers as $name => $column) {
            $replace .= '$rest->setHeaders(\'' . $name . '\', \'value\');' . "\n";
        }
        $replace = rtrim($replace, "\n");
        if (empty($replace)) {
            $search = ["{{ set_header_params }}\n", "{{set_header_params}}\n"];
        } else {
            $search = ['{{ set_header_params }}', '{{set_header_params}}'];
        }

        $stub = str_replace($search, $replace, $stub);

        return $this;
    }

    protected function replaceSetQueryParams(&$stub)
    {
        $replace = '';
        foreach ($this->queryParams as $name => $column) {
            $replace .= '$rest->setQueryParams(\'' . $name . '\', \'value\');' . "\n";
        }

        $replace = rtrim($replace, "\n");

        if (empty($replace)) {
            $search = ["{{ set_query_params }}\n", "{{set_query_params}}\n"];
        } else {
            $search = ['{{ set_query_params }}', '{{set_query_params}}'];
        }
        $stub = str_replace($search, $replace, $stub);

        return $this;
    }

    protected function replaceSetBodyParams(&$stub)
    {
        $replace = '';
        foreach ($this->bodyParams as $name => $column) {
            $replace .= '$rest->setHttpData(\'' . $name . '\', \'value\');' . "\n";
        }
        $replace = rtrim($replace, "\n");

        if (empty($replace)) {
            $search = ["{{ set_body_params }}\n", "{{set_body_params}}\n"];
        } else {
            $search = ['{{ set_body_params }}', '{{set_body_params}}'];
        }

        $stub = str_replace($search, $replace, $stub);

        return $this;
    }

    /**
     * 获取模板文件地址
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/sdk.demo.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = app()->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . '/../..' . $stub;
    }
}
