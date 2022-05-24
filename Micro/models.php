<?php

/**@var $nav API\Core\Utils\NavBuilder\NavBuilder */


$user_avatar = '';
$userID  = 0;
use API\Core\Session\Session;

if( Session::loggedUserID()){
    $userID  = (int)Session::loggedUserID();
    $user_avatar = Session::loggedUserAvatar();
}


$nav->link('MICRO1', 'Micro.index', 'fa-cannabis');
$nav->link('MICRO2', 'Micro.demo1', 'fa-cogs');


$nav->drop('MICRO3')
    ->entry('LABEL_DEMO1', 'Micro.showWebPage','fa-star',['webView' => 'controllers'] )
    ->entry('LABEL_DEMO2', 'Micro.showWebPage','fa-database',['webView' => 'models'] )
    ->entry('LABEL_DEMO3', 'Micro.showWebPage','fa-eye',['webView' => 'views'] )
    ->entry('LABEL_DEMO4', 'Micro.showWebPage','fa-road',['webView' => 'routing'] )
    ->entry('LABEL_DEMO5', 'Micro.showWebPage','fa-atom',['webView' => 'middleware'] )
    ->entry('LABEL_DEMO6', 'Micro.showWebPage','fa-question',['webView' => 'helpers'] );



//$nav->admin()
//    ->entry('REGISTER', 'authUserService.create', 'fa-user-plus', [],'GUEST')
//    ->entry('LOG_IN', 'authUserService.index', 'fa-sign-in-alt', [],"GUEST")
//    ->avatar($user_avatar, 'foo.showDemos', ['id'=>$userID],"USER")
//    ->entry('LOG_OUT', 'authUserService.clearSession', 'fa-sign-out-alt', [],"USER");

