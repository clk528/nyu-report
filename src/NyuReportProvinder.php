<?php

namespace clk528\NyuReport;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;

class NyuReportProvinder extends ServiceProvider
{
    protected $routeMiddleware = [
        'hik.auth' => \clk528\NyuReport\Middleware\HikAuthenticate::class,
        'wechat.auth' => \clk528\NyuReport\Middleware\WeChatAuthenticate::class
    ];

    protected $commands = [
        Command\ResetQuestionnaireEmailIsReadCommand::class,
        Command\SendQuestionnaireEmailCommand::class
    ];

    public function boot()
    {
        $used = config('nyu.report_used', true);
        
        if (file_exists($routes = __DIR__ . '/routes/routes.php') && $used) {
            $this->loadRoutesFrom($routes);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nyu-report-views');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'nyu-report-migrations');
        }
    }

    public function register()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        $this->commands($this->commands);
    }
}
