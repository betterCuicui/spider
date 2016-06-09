<?php

/**
 * Created by PhpStorm.
 * User: cuicui
 * Date: 2016/6/3
 * Time: 17:00
 */
class MenuAction extends Action{
    public function index(){
        $this->display();
    }
    //判断用户是否登录
    public function CheckmSession() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect("Admin/login");
        }
    }
}