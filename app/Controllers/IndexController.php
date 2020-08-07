<?php

namespace App\Controllers;
use App\Models\Job;
use App\Models\Project;



class IndexController extends BaseController {
    
    public function indexAction(){
       
        $jobs = Job::all();
       
        $projects = Project::all(); 

        $name = 'Hector Benitez';
        $limitMonths = 2000;

        return $this->renderHTML('index.twig', [
            'name' => 'Christopher',
            'jobs'=> $jobs,
        ]);
    }
}