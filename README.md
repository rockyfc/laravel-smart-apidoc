# laravel-smart-apidoc
根据程序逻辑自动生成laravel文档。

简介
1. 实时文档。文档根据动态逻辑生成，逻辑修改，文档会随之自动更新。
2. 有效解决文档和程序的脱钩问题。文档和实际程序的脱钩问题是常规手动维护文档产生的最大的问题。
3. 执行路由的双向检查。
- 可以检查程序中的空路由，并抛出异常。
- 可以检查没有指定路由的空接口（action），并抛出异常。
4. 开放文档程序接口给调用者。
- 文档程序向外部公开了获取所有接口文档的接口。便于自定义文档系统。
    
安装方法
```
composer require rockyfc/laravel-smart-apidoc
```

安装完成后，在`config/app.php`配置文件中的`providers`的最后一行，添加如下代码：

```
\Smart\ApiDoc\Providers\DocServiceProvider::class
```

命令行执行如下命令：
```$xslt
php artisan smart:install-doc
```

完成安装。

访问 `http://xxx.xx/smart-doc` 查看文档首页。
