<?php
namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;


class UserController extends BaseController {


    public function getUser() {
        return $this->renderHTML('user.twig');
    }

    public function storeUser($request){
        $responseMessage = null;

        if($request->getMethod() == 'POST'){

        //var_dump($request);
            $postData = $request->getParsedBody();

            $userValidator = v::key('username', v::stringType()->notEmpty())
                  ->key('email', v::stringType()->notEmpty())
                  ->key('password', v::stringType()->notEmpty());

                  try{
                
                    $userValidator->assert($postData);
                    $postData = $request->getParsedBody();

                    $password=password_hash($postData['password'], PASSWORD_DEFAULT);
    
                    $user = new User();
                    $user->username = $postData['username'];
                    $user->email = $postData['email'];
                    $user->password = $password;
                    $user->save();
                    
                    $responseMessage = 'Saved';

                } catch(\Exception $e){
                    $responseMessage = $e->getMessage();
                }

            
            return $this->renderHTML('user.twig', [
                'responseMessage' => $responseMessage
                ]);
                
        }
    }
}