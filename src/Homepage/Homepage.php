<?php

class Homepage extends General {

    const VIEW_PATH = 'Homepage/views/';

    public function index(){
        $this->render('Homepage/views/home.php');
    }

}
