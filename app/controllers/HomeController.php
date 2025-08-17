<?php
class HomeController extends Controller {
  public function index(){
    if (!is_logged_in()) {  redirect('auth/login'); exit; }
    $this->view('home', ['title' => 'Dashboard']);
  }
}
