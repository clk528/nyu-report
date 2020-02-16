<?php

use Illuminate\Routing\Router;


Route::namespace('clk528\NyuReport\Controller')->middleware('web')->group(function (Router $router) {

    $router->view("jj5tlku.html", 'nyu-report::email.questionnaire-notice-to-specified');

    $router->get('clk/sso', 'SsoController@toSso')->name('clk.sso');

    $router->middleware('hik.auth')->prefix('ill')->group(function (Router $router) {
        $router->get('/', 'IllController@index');
        $router->post('submit', 'IllController@store');
        $router->post('getIll', 'IllController@getIll');
    });

    $router->middleware('hik.auth')->prefix('virus')->group(function (Router $router) {
        $router->post('submit', 'VirusController@store');
        $router->post('getVirus', 'VirusController@getVirus');
    });

    $router->view('healthcheck', 'h5.index')->middleware('hik.auth')->name('ill');

    $router->view('clk', 'h5.index')->middleware('wechat.auth');
    $router->view('wechat', 'h5.index')->middleware('wechat.auth');

    $router->get('wechat/redirect', 'Wechat\WeChatController@redirect')->name('ill.wechat.redirect');
});
