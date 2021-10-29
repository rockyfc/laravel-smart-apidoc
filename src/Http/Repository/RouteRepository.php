<?php

namespace Smart\ApiDoc\Http\Repository;

use ReflectionException;
use Smart\ApiDoc\Services\ConfigService;
use Smart\ApiDoc\Services\SdkDemoService;
use Smart\Common\Exceptions\ResourceMissDataException;
use Smart\Common\Helpers\MarkdownCreator;
use Smart\Common\Services\DocService;
use Smart\Common\Services\UriParserService;

class RouteRepository
{
    /**
     * @var DocService
     */
    protected $service;

    /**
     * RouteRepository constructor.
     */
    public function __construct()
    {
        $this->service = new DocService();
    }

    /**
     * @throws ReflectionException
     * @return array
     */
    public function controllers()
    {
        return $this->service->controllerComments();
    }

    /**
     * @param $routeName
     * @throws ResourceMissDataException
     * @throws ReflectionException
     * @return array
     */
    public function actionWithSdkDemo($routeName)
    {
        $action = $this->service->action($routeName, $route);
        //echo (new UriParserService($route))->version();exit;
        //dd($action);
        $service = new SdkDemoService($route);
        //$service->setHeaders();
        if (isset($action['request']['input'])) {
            $service->setBodyParams((array)$action['request']['input']);
        }
        $service->setQueryParams($action['uriParams']);
        $action['sdk']['php'] = $service->getDemoContent();

        return $action;
    }

    /**
     * 获取所有的md文件
     * @return array
     */
    public function getMdFiles()
    {
        return array_merge(
            $this->getSystemMdFiles(),
            $this->getCustomMdFiles()
        );
    }

    /**
     * 生成一个markdown语法的action文档
     *
     * @param string $actionName
     * @throws ResourceMissDataException
     * @throws ReflectionException
     * @return string
     */
    public function markdown(string $actionName)
    {
        $action = $this->actionWithSdkDemo($actionName);

        $creator = new MarkdownCreator();
        $creator->title($action['title'] . '【' . $action['controller']['title'] . '】');
        $creator->p('创建时间', $action['created_at'] . ' by ' . $action['author'][0]['authorName']);

        $creator->div('描述', [$action['desc']]);
        $creator->div('路由', [$action['methods'][0], $action['name']]);

        $creator->line('公共输入参数', '同URL请求参数一样，放到url上发送。');
        $common = $this->inputParams($action['commonRequest']);
        $creator->table($common['title'], $common['items']);

        $creator->line('URL参数', 'Http Query String Parameters');
        $common = $this->inputParams($action['uriParams']);
        $creator->table($common['title'], $common['items']);

        $creator->line('输入参数', 'Http Body Data');
        $body = $this->inputParams(@$action['request']['input']);
        $creator->table($body['title'], $body['items']);

        $creator->line('输出参数', '（返回值）');
        $output = $this->outputParams(@$action['response']['output']);
        $creator->table($output['title'], $output['items']);

        return $creator->render();
    }

    /**
     * @return array|array[]
     */
    protected function getSystemMdFiles()
    {
        if ($this->mdFilesConfig()['system'] === false) {
            return [];
        }
        //echo realpath(__DIR__ . '/../../../httpCode.README.MD');exit;

        return [
            $this->formatFileArray(realpath(__DIR__ . '/../../../HttpCode.README.MD')),
        ];
    }

    /**
     * @return array
     */
    protected function getCustomMdFiles()
    {
        $files = [];
        foreach ($this->mdFilesConfig()['custom'] as $file) {
            $files[] = $this->formatFileArray($file);
        }

        return $files;
    }

    /**
     * @param $filename
     * @return array
     */
    protected function formatFileArray($filename)
    {
        $file = explode('.', basename($filename));

        return [
            'key' => md5($filename),
            'name' => array_shift($file),
            'path' => $filename,
        ];
    }

    /**
     * @return array
     */
    protected function mdFilesConfig()
    {
        $files = ConfigService::mdFiles();
        return $files ? $files : ['system' => false, 'custom' => []];
    }

    /**
     * @param $params
     * @return array
     */
    protected function inputParams($params)
    {
        $title = ['名称', '类型', '是否必填', '可选值', '默认值', '描述'];

        $items = [];

        $params = (array)$params;

        foreach ($params as $key => $item) {
            $options = $this->options($item['options']);
            $row = [];
            $row[] = $key;
            $row[] = $item['type'];
            $row[] = $item['required'] ? '是' : '否';
            $row[] = $options ? implode(', ', $options) : ' ';
            $row[] = $item['default'] ? $item['default'] : ' ';
            $row[] = $item['comment'] ? $item['comment'] : ' ';
            $items[] = $row;
        }

        return [
            'title' => $title,
            'items' => $items,
        ];
    }

    /**
     * @param $options
     * @return array
     */
    protected function options($options)
    {
        $tmp = [];
        foreach ($options as $k => $val) {
            if (is_array($val)) {
                $tmp[] = $k;

                continue;
            }

            $tmp[] = $val;
        }

        return $tmp;
    }

    /**
     * @param $params
     * @return array
     */
    protected function outputParams($params)
    {
        $title = ['名称', '类型', '可选值', '描述'];

        $items = [];
        $params = (array)$params;
        foreach ($params as $key => $item) {
            $row = [];
            $row[] = $key;
            $row[] = $item['type'];
            $row[] = $item['options'] ? implode(', ', $item['options']) : ' ';
            $row[] = $item['comment'] ? $item['comment'] : ' ';
            $items[] = $row;
        }

        return [
            'title' => $title,
            'items' => $items,
        ];
    }
}
