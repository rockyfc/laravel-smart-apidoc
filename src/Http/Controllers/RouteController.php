<?php

namespace Smart\ApiDoc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Smart\ApiDoc\Http\Repository\RouteRepository;
use Smart\ApiDoc\Services\ConfigService;
use Smart\Common\Exceptions\ResourceMissDataException;
use Smart\Common\Helpers\Parser;
use Smart\Common\Services\DocService;

class RouteController extends Controller
{
    /**
     * @var DocService
     */
    private $service;

    /**
     * @var RouteRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $files;

    /**
     * RouteController constructor.
     * @param DocService $service
     * @param RouteRepository $repository
     * @throws \ReflectionException
     */
    public function __construct(DocService $service, RouteRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;

        // print_r($this->sliceByModule());
        view()->share('menus', $this->sliceByModule());
        view()->share('controllers', $this->service->controllerComments());
        view()->share('files', $this->files = $repository->getMdFiles());
    }

    /**
     * 根据关键字查找action
     *
     * @param Request $request
     * @throws \ReflectionException
     * @return View
     */
    public function filter(Request $request)
    {
        $keyword = $request->input('keyword');
        $module = $request->input('module');
        $keyword = addslashes($keyword);
        $actions = $this->service->actions($keyword, $module);

        if ($this->service->error) {
            $request->session()->flash('_errors', $this->service->error);
        }
        // print_r($actions);exit;

        return view('doc::route.filter', [
            'actions' => $actions,
        ]);
    }

    /**
     * @throws \ReflectionException
     * @return View
     */
    public function controllers()
    {
        $controllers = $this->service->controllerComments();

        return view('doc::route.index', [
            'controllers' => $controllers,
        ]);
    }

    /**
     * @param Request $request
     * @throws ResourceMissDataException
     * @throws \ReflectionException
     * @return View
     */
    public function actionsByController(Request $request)
    {
        $controllerName = $request->input('name') or exit('error');
        $actions = $this->service->actionCommentsByController($controllerName);

        if ($this->service->error) {
            $request->session()->flash('_errors', $this->service->error);
        }
        // print_r($actions);exit;
        // print_r($this->service->controllerComment($controllerName));exit;
        return view('doc::route.controller-actions', [
            'actions' => $actions,
            'controller' => $this->service->controllerComment($controllerName),
        ]);
    }

    /**
     * 显示接口详情
     * @param Request $request
     * @throws \ReflectionException|ResourceMissDataException
     * @return View
     */
    public function view(Request $request)
    {
        $name = $request->input('name') or exit('miss uri');
        $action = $this->repository->actionWithSdkDemo($name);

        // print_r($action);exit;
        return view('doc::route.view', [
            'action' => $action,
        ]);
    }

    /**
     * @param Request $request
     * @throws ResourceMissDataException
     * @throws \ReflectionException
     * @return string
     */
    public function markdown(Request $request)
    {
        $name = $request->input('name') or exit('miss uri');
        $content = $this->repository->markdown($name);

        return '<pre>' . $content . '</pre>';
    }

    /**
     * @param Request $request
     * @throws ResourceMissDataException
     * @throws \ReflectionException
     * @return View
     */
    public function resources(Request $request)
    {
        $class = $request->get('class');

        return view('doc::route.resources', [
            'data' => $this->service->resource($class),
        ]);
    }

    /**
     * 获取文件的内容
     * @param $file
     * @return View
     */
    public function file($file)
    {
        $files = array_column($this->files, null, 'key');

        $Parser = new Parser();

        return view('doc::route.file', [
            'content' => $Parser->makeHtml(file_get_contents($files[$file]['path'])),
        ]);
    }

    /**
     * 按模块拆分路由
     * @return array
     */
    protected function sliceByModule()
    {
        $modules = [];
        foreach (ConfigService::modules() as $module) {
            $row = $module;
            foreach ($this->service->validRoutes() as $route) {
                if (!isset($module['uriPrefix'])) {
                    continue;
                }

                if (Str::startsWith($route->getName(), $module['uriPrefix'])) {
                    $controller = get_class($route->getController());
                    $row['routes'][$controller] = substr($route->getName(), 0, strrpos($route->getName(), '.'));
                }
            }
            $modules[] = $row;
        }

        return $modules;
    }
}
