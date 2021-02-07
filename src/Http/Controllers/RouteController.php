<?php

namespace Smart\ApiDoc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ReflectionException;
use Smart\ApiDoc\Http\Repository\RouteRepository;
use Smart\Common\Exceptions\ResourceMissDataException;
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
     * @throws ReflectionException
     */
    public function __construct(DocService $service, RouteRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;

        view()->share('controllers', $this->service->controllerComments());
        view()->share('files', $this->files = $repository->getMdFiles());
    }

    /**
     * 根据关键字查找action
     *
     * @param Request $request
     * @throws ReflectionException
     * @return View
     */
    public function filter(Request $request)
    {
        $keyword = $request->input('keyword');
        $keyword = addslashes($keyword);
        $actions = $this->service->actions($keyword);

        if ($this->service->error) {
            $request->session()->flash('_errors', $this->service->error);
        }
        //print_r($this->service->error);exit;

        return view('doc::route.filter', [
            'actions' => $actions,
        ]);
    }

    /**
     * @throws ReflectionException
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
     * @throws ReflectionException
     * @throws ResourceMissDataException
     * @return View
     */
    public function actionsByController(Request $request)
    {
        $controllerName = $request->input('name') or die('error');
        $actions = $this->service->actionCommentsByController($controllerName);

        if ($this->service->error) {
            $request->session()->flash('_errors', $this->service->error);
        }
        //print_r($actions);exit;
        //print_r($this->service->controllerComment($controllerName));exit;
        return view('doc::route.controller-actions', [
            'actions' => $actions,
            'controller' => $this->service->controllerComment($controllerName),
        ]);
    }

    /**
     * 显示接口详情
     * @param Request $request
     * @throws ReflectionException|ResourceMissDataException
     * @return View
     */
    public function view(Request $request)
    {
        $name = $request->input('name') or die('miss uri');
        $action = $this->repository->actionWithSdkDemo($name);

        //print_r($action);exit;
        return view('doc::route.view', [
            'action' => $action,
        ]);
    }

    /**
     * @param Request $request
     * @throws ReflectionException
     * @throws ResourceMissDataException
     * @return string
     */
    public function markdown(Request $request)
    {
        $name = $request->input('name') or die('miss uri');
        $content = $this->repository->markdown($name);

        return '<pre>' . $content . '</pre>';
    }

    /**
     * @param Request $request
     * @throws ReflectionException
     * @throws ResourceMissDataException
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

        return view('doc::route.file', [
            'content' => file_get_contents($files[$file]['path']),
        ]);
    }
}
