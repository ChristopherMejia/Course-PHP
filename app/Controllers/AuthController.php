<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;
use Laminas\Diactoros\Response\RedirectResponse;



class AuthController extends BaseController {
    
    public function getLogin(){

        return $this->renderHTML('login.twig');

    }

    public function getLogout(){

        unset($_SESSION['id_user']);
        $response = new RedirectResponse('/curso-php/login');
        return $response;

    }

    public function postLogin($request){

        $responseMessage = null;
 
        $postData = $request->getParsedBody();

        
        $user = User::where('email' , $postData['email'])->first();
        //var_dump($user['password']);

        if($user){
            if(password_verify($postData['password'], $user->password)){
                $_SESSION['id_user'] = $user->id_user; 
                $response = new RedirectResponse('/curso-php/admin');
                return $response;
            }else {
                $responseMessage = 'Bad Credentials';
            }
        } else{
            $responseMessage = 'Bad Credentials';
        
        }
        
        return $this->renderHTML('login.twig',[
            'responseMessage' => $responseMessage
        ]);
        
    }
}