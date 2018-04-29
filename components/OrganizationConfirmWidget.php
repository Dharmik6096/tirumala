<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

/*
 * Description: This widget used to organization selection dynamically
 * By: Dhara
 * Date: 3-5-2016
 */

class OrganizationConfirmWidget extends Widget{

    
    public function init(){
            // add your logic here
    }


    public function run(){
//            $values = \webvimark\modules\UserManagement\models\User::getUserOrganizations(\Yii::$app->session->get('UserCode'));
            $values = GeneralFunctions::getUserOrganization(\Yii::$app->session->get('UserCode'));
            return $this->render('Organization',['array'=>$values]);
    }

}
?>
