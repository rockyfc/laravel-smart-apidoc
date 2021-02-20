# laravel-smart-apidoc
根据程序逻辑自动生成laravel文档。

###简介
1. 实时文档。文档根据动态逻辑生成，逻辑修改，文档会随之自动更新。
2. 有效解决文档和程序的脱钩问题。文档和实际程序的脱钩问题是常规手动维护文档产生的最大的问题。
3. 执行路由的双向检查。
- 可以检查程序中的空路由，并抛出异常。
- 可以检查没有指定路由的空接口（action），并抛出异常。
4. 开放文档程序接口给调用者。
- 文档程序向外部公开了获取所有接口文档的接口。便于自定义文档系统。
    
###安装方法

命令行执行：
```shell
composer require rockyfc/laravel-smart-apidoc
```


在命令行执行如下命令：
```shell
php artisan smart:install-doc
```

完成安装，访问 `http://xxx.xx/smart-doc` 查看文档首页。

###使用方法

Laravel框架的表单层RequestForm和资源层Resource为该文档程序的实现的基石，也就是说，程序中只要使用了表单和资源两大组件，即可实现强大的文档功能。但
不可否认，编写文档程序过程中，始终坚持文档绝不影响业务实现、文档决不对业务造成代码污染、文档绝不影响laravel内置的优秀特性为大前提。可以让使用者在业务
开发中无需额外关心文档如何生成。

文档程序在开发中，对于几种典型的增删改查业务场景做了支持。

**列表查询接口**

列表查询接口往往涉及到返回值存在多条资源，且伴有分页、排序、等功能，如何让文档智能识别是首要问题。

**识别当前接口为数据列表：**

接口的注释内容中的`@return`标签返回的值类型，标注为`Illuminate\Http\Resources\Json\ResourceCollection`或者它的子类，文档程序便可以识别
此接口的返回值是一个数据列表。当然，强大的IDE也实现了自动生成注释的功能，无需手动输入。示例代码如下：
```php

    /**
     * 接口标题
     * ...
     * 
     * @return AnonymousResourceCollection
     * ...
     */
    public function index(FormRequest $request)
    {
        //code...
    }

```

**识别当前列表接口是否带有分页：**

在注释中添加一个`@see`标签，并且标签的内容为`Illuminate\Pagination\AbstractPaginator`或者它的子类，便可被识别为列表接口带有分页功能。
```php

    /**
     * 接口标题
     * ...
     * @see LengthAwarePaginator
     * @return AnonymousResourceCollection
     * ...
     */
    public function index(FormRequest $request)
    {
        //code...
    }

```

**识别排序功能：**

排序功能需要显示的指明可以按照哪些字段排序，并且允许这些字段在客户端来输入，所以，识别排序需要在表单层中实现一个`sort()`函数来标记排序。
```php
    /**
     * 需要排序的字段
     * @return string[]
     */
    public function sorts()
    {
        return ['id', 'updated_at'];
    }

```

**识别当前接口为创建，编辑，删除等接口：**

除了带有分页的接口相对麻烦一点，其他接口都变的很简单，只需正确使用http动词get，post，put，delete即可，action的注释用IDE自动生成就好。

以下以分类接口为例子，为大家展示一个增删改查接口的使用范例：
```php

<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CateRepository;
use App\Http\Requests\CateRequest;
use App\Http\Resources\CateResource;
use App\Models\Cate;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

/**
 * 分类接口
 *
 * 这是一个文章分类的接口管理，其中包括增删改查。
 *
 * @author 张三
 * @date 2021-02-07
 * @package App\Http\Controllers
 */
class CateController extends \Illuminate\Routing\Controller
{
    /**
     * @var CateRepository
     */
    protected $repository;

    /**
     * @param CateRepository $repository
     */
    public function __construct(CateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 列表
     *
     * 列表数据，支持分页、自定义排序、按需获取。
     * @param CateRequest $request
     * @see LengthAwarePaginator
     * @see CateResource
     * @return AnonymousResourceCollection
     * @throws ValidationException
     * @author 张三
     * @date 2021-02-07
     */
    public function index(CateRequest $request)
    {
        //创建一条查询
        $query = $this->repository->search(
            $request->validated(), //获取验证后的数据
            $request->getFilteredRelations(), //加载关联
            $request->getResolvedSorts() //加载排序
        );

        //返回集合
        return CateResource::collection(
            $query->paginate($request->getPerPage())
        );
    }

    /**
     * 详情
     * @param Cate $cate
     * @param CateRequest $request
     * @return CateResource
     * @throws ValidationException
     * @author 张三
     * @date 2021-02-07
     */
    public function show(Cate $cate, CateRequest $request)
    {
        //加载关联
        if ($relations = $request->getFilteredRelations()) {
            $cate->load($relations);
        }

        //返回一条资源
        return new CateResource(
            $cate
        );
    }

    /**
     * 新建
     * @param CateRequest $request
     * @return CateResource
     * @author 张三
     * @date 2021-02-07
     */
    public function store(CateRequest $request)
    {
        $model = new Cate();
        $model->fill($request->validated());
        $model->save();

        return new CateResource(
            $model
        );
    }

    /**
     * 编辑
     * @param CateRequest $request
     * @param Cate $cate
     * @return CateResource
     * @author 张三
     * @date 2021-02-07
     */
    public function update(CateRequest $request, Cate $cate)
    {
        $cate->fill($request->validated());
        $cate->save();

        return new CateResource(
            $cate
        );
    }

    /**
     * 删除
     * @param Cate $cate
     * @return Response
     * @throws Exception
     * @author 张三
     * @date 2021-02-07
     */
    public function destroy(Cate $cate)
    {
        $cate->delete();

        return response(null, 204);
    }

}

```

以上为文档程序所推荐的接口的常规写法。你可以手动实现它，使用"rockyfc/laravel-smart-gii"脚手架程序可以生成以上风格的代码，增删改查无需手动实现。
