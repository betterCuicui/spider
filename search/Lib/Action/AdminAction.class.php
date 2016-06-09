<?php
/**
 * Created by PhpStorm.
 * User: cuicui
 * Date: 2016/5/25
 * Time: 10:52
 */
class AdminAction extends Action{
    public function login(){
        $this->display();
    }
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