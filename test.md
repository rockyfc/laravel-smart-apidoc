# 中间件

- [介绍](#introduction)
- [定义中间件](#defining-middleware)
- [注册中间件](#registering-middleware)
    - [全局中间件](#global-middleware)
    - [为路由分配中间件](#assigning-middleware-to-routes)
    - [中间件群组](#middleware-groups)
    - [中间件的排序](#sorting-middleware)
- [中间件参数](#middleware-parameters)
- [Terminable 中间件](#terminable-middleware)

<a name="introduction"></a>
## 介绍

中间件提供了一种方便的机制来过滤进入应用程序的 HTTP 请求。例如 , Laravel 包含一个验证用户身份的中间件。 如果用户未能通过认证，中间件会把用户重定向到登录页面。 反之，用户如果通过验证， 中间件将把请求进一步转发到应用程序中。

当然，除了验证身份外，还可以编写其他的中间件来执行各种任务。例如：CORS 中间件可以负责为所有的应用返回的 responses 添加合适的响应头。日志中间件可以记录所有传入应用的请求。

Laravel 自带了一些中间件，包括身份验证、CSRF 保护等。所有的这些中间件都位于 `app/Http/Middleware `目录。

<a name="defining-middleware"></a>
## 定义中间件

使用 `make:middleware` 命令来创建新的中间件:

    php artisan make:middleware CheckAge

该命令会在`app/Http/Middleware`目录下生成新的  `CheckAge` 类。在这个中间件中，我们仅允许 `age` 参数大于 200 的请求对路由进行访问，否则将重定向到 `home` 页面。

    <?php

    namespace App\Http\Middleware;

    use Closure;

    class CheckAge
    {
        /**
         * 处理传入的请求
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            if ($request->age <= 200) {
                return redirect('home');
            }

            return $next($request);
        }
    }

正如你所见，如果给定的  `age` 参数小于或者等于 `200`, 这个中间件将会返回一个 HTTP 重定向到客户端；否则这个请求将会通过，进一步传递到应用层中。 要让请求继续传递到应用层中（即允许「通过」中间件验证），只需要将`$request`作为参数来调用函数 `$next`即可 。

最好将中间件想象成一系列层次，`HTTP`请求必须通过它们才能进入你的应用层。每一层都会检查请求（是否符合中间件要求），而后决定通过或拒绝访问应用。

> {提示} 所有的中间件都是通过 [服务容器](/docs/{{version}}/container) 解析的, 因此，你可以在你的中间件构造函数中键入你需要的任何依赖。





### 前置 & 后置中间件

中间件是在请求之前或之后执行，取决于中间件本身。例如， 下面的中间件将在应用处理请求之前执行一些任务：

    <?php

    namespace App\Http\Middleware;

    use Closure;

    class BeforeMiddleware
    {
        public function handle($request, Closure $next)
        {
            // 执行一些任务

            return $next($request);
        }
    }

然后，下面中间件是在应用请求之后执行一些任务：

    <?php

    namespace App\Http\Middleware;

    use Closure;

    class AfterMiddleware
    {
        public function handle($request, Closure $next)
        {
            $response = $next($request);

            // 执行一些任务

            return $response;
        }
    }

<a name="registering-middleware"></a>
## 注册中间件

<a name="global-middleware"></a>
### 全局中间件

如果你希望中间件在应用处理每个`HTTP`请求期间运行， 只需要在 `app/Http/Kernel.php` 中的  `$middleware` 属性中列出这个中间件。

<a name="assigning-middleware-to-routes"></a>
### 为路由分配中间件

假设你想为指定的路由分配中间件 ， 首先应该在 `app/Http/Kernel.php` 文件内为该中间件分配一个键。默认情况下，该类中的 `$routeMiddleware` 属性下包含了 Laravel 内置的中间件。若要加入自定义的中间件，只需把它附加到列表后并为其分配一个自定义键。例如：

    // 在 App\Http\Kernel 类中...

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

一旦在 `HTTP` 内中定义好了中间件，就可以通过 `middleware` 方法为路由分配中间件：

    Route::get('admin/profile', function () {
        //
    })->middleware('auth');

你也可以为路由分配多个中间件：

    Route::get('/', function () {
        //
    })->middleware('first', 'second');

分配中间件时，还可以传递完整的类名：

    use App\Http\Middleware\CheckAge;

    Route::get('admin/profile', function () {
        //
    })->middleware(CheckAge::class);





<a name="middleware-groups"></a>
### 中间件群组

某些时候你可能希望使用一个键把多个中间件打包成一个组，以便更容易地分配给路由。你可以使用`HTTP` 内核中的`$middlewareGroups` 属性 。

Laravel 内置了开箱即用的 `web` 和 `api` 中间件组，其中包含你可能希望应用于 `Web UI` 和 `API`路由的常用中间件：

    /**
     * 应用程序的路由中间件组
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'auth:api',
        ],
    ];

中间件组可以使用与单个中间件相同的语法将自身分配给路由和控制器动作。同样，中间件组使得一次将多个中间件分配给一个路由更加方便：

    Route::get('/', function () {
        //
    })->middleware('web');

    Route::group(['middleware' => ['web']], function () {
        //
    });

    Route::middleware(['web', 'subscribed'])->group(function () {
        //
    });

> {提示} `RouteServiceProvider` 默认将 `web` 中间件组自动应用到 `routes/web.php`。

<a name="sorting-middleware"></a>
### 中间件排序

很少情况下，你可能需要中间件以特定的顺序执行，但是当它们被分配到路由时，你无法控制它们的顺序。在这种情况下，可以使用 `app/Http/Kernel.php` 文件中的 `$middlewarePriority` 属性指定中间件的优先级：

    /**
     * 中间件的优先级排序列表
     *
     * 将会强制非全局中间件始终保持给定的顺序。
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];





<a name="middleware-parameters"></a>
## 中间件参数

中间件还可以接收额外的参数。例如，如果你的应用程序需要在执行给定操作之前验证用户是否为给定的「角色」 ， 你可以创建一个 `CheckRole` 中间件，由它来接收「角色」名称作为附加参数。

附加的中间参数会在 `$next` 参数之后传递给中间件：

    <?php

    namespace App\Http\Middleware;

    use Closure;

    class CheckRole
    {
        /**
         * 处理传入的请求
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @param  string  $role
         * @return mixed
         */
        public function handle($request, Closure $next, $role)
        {
            if (! $request->user()->hasRole($role)) {
                // 重定向
            }

            return $next($request);
        }

    }

定义路由时通过一个 `:` 来隔开中间件名称和参数来指定中间件参数。多个参数就使用逗号分隔：

    Route::put('post/{id}', function ($id) {
        //
    })->middleware('role:editor');

<a name="terminable-middleware"></a>
## Terminable 中间件

有时可能需要在 `HTTP` 响应之后做一些工作。 如果你在中间件上定义了一个 `terminate` 方法 , 并且你使用的是 FastCGI, 那么 `terminate` 方法会在响应发送到浏览器之后自动调用。

    <?php

    namespace Illuminate\Session\Middleware;

    use Closure;

    class StartSession
    {
        public function handle($request, Closure $next)
        {
            return $next($request);
        }

        public function terminate($request, $response)
        {
            // 存储 session 数据
        }
    }

`terminate` 方法应该同时接收请求和响应。定义了这个中间件之后， 别忘了将它添加到路由列表或者 `app/Http/Kernel.php` 文件的全局中间件中。

当你在中间件上调用 `terminate` 方法的时候, Laravel 将从 [服务容器](/docs/{{version}}/container) 中解析出一个新的中间件实例。如果在调用 `handle` 和  `terminate` 方法的同时使用相同的中间件实例， 请使用容器的 `singleton` 方法注册中间件， 通常这应该在 `AppServiceProvider.php` 文件中的 `register` 方法中完成:

    use App\Http\Middleware\TerminableMiddleware;

    /**
     * 注册任意应用服务
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TerminableMiddleware::class);
    }
