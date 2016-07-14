<?php
namespace App\Http\Controllers\Member;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller {

    
    public function entityUrl($entity, $methods, Request $request){

        $appHelper = new  \Serverfireteam\Panel\libs\AppHelper(); 

   		 $panel_path = \Config::get('panel.controllers');
            if ( isset($panel_path) ){               
               $controller_path = '\\'.$panel_path.'\\'.ucfirst($entity).'Controller';                
            } else {
                $controller_path = $appHelper->getNameSpace().'Http\Controllers\Member\\'.ucfirst($entity).'Controller';            
            }    
               
        try{
            $controller = \App::make($controller_path);                
        }catch(\Exception $ex){
            throw new \Exception("Can not found the Controller ( $controller_path ) ");               
        }

        if (!method_exists($controller, $methods)){                
            throw new \Exception('Controller does not implement the CrudController methods!');               
        } else {
            return $controller->callAction($methods, array('entity' => $entity, 'request'=>$request));
        }
    
    }    
}


