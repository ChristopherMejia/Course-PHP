<?php
namespace App\Controllers;
use App\Models\Auth;

class AdminController extends BaseController{
    public function getIndex(){
        return $this->renderHTML('admin.twig');
    }
}