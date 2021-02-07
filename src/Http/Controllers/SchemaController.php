<?php

namespace Smart\ApiDoc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ReflectionException;
use Smart\ApiDoc\Http\Repository\RouteRepository;
use Smart\Common\Exceptions\ResourceMissDataException;
use Smart\Common\Services\DocService;

class SchemaController extends Controller
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
     * RouteController constructor.
     * @param DocService $service
     * @param RouteRepository $repository
     */
    public function __construct(DocService $service, RouteRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    /**
     * 根据action名称，获取action详情
     * @param Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        $name = $request->input('name') or die('miss action name param');

        try {
            return $this->service->action($name);
        } catch (ReflectionException $e) {
        } catch (ResourceMissDataException $e) {
        }

        return [];
    }
}
