<?php

namespace clk528\NyuReport;

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
        if (file_exists($routes = __DIR__ . '/routes/routes.php')) {
            $this->loadRoutesFrom($routes);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nyu-report');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'clk528-nyu-report-migrations');
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
