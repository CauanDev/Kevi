<?php

use App\Http\Controller\OpenAiController;
use App\Http\Controller\ScheduleController;
use App\Http\Controller\ZApiController;

use Slim\App;

return function (App $app) 
{
    $app->get('/', [OpenAiController::class,'teste']);
    $app->group('/api', function ($app) {
        
        $app->post('/chatgpt', [OpenAiController::class, 'index']);
        $app->post('/question', [OpenAiController::class, 'question']);

        $app->post('/store-schedule', [ScheduleController::class, 'store']);
        $app->get('/schedules',[ScheduleController::class, 'index']);
        $app->post('/destroy',[ScheduleController::class,'destroy']);
        $app->post('/schedule-filter',[ScheduleController::class,'filter']);
        
        $app->post('/send',[ZApiController::class,'send']);
        $app->post('/verify-contact',[ZApiController::class,'index']);
        $app->get('/verify-instance',[ZApiController::class,'instance']);
        $app->get('/disconnect',[ZApiController::class,'destroy']);
        $app->post('/connect',[ZApiController::class,'store']);
        $app->get('/contact',[ZApiController::class,'contact']);
    });
};
