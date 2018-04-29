<?php

namespace app\modules\restservices\controllers;

use app\modules\restservices\controllers\RestController;
use app\modules\restservices\models\BiplModel;


/**
 * Default controller for the `restservices` module
 */
class BiplController extends RestController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    
    public function actionBiplServices(){  
        $model=new BiplModel();
        return $model->manipulation($this->biplRequest());        
    }
       
}
