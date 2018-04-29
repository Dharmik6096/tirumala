<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SystemConfiguration */

$this->title = Yii::t('app', 'Create System Configuration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Configurations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="system-configuration-create">
    <div class="panel panel-main">
        <div class="panel-heading">System Configuration</div>
        <div class="panel-body">           

            <?php 
            
            echo $this->render('_form', ['model' => $model,'unions'=>$unions,'records'=>$records,'unionArray'=>$unionArray]);
            //if($flag=='village') {?>
            <?php
            /*$this->render($view, ['flag'=>$flag,'model' => $model,'unions'=>$unions
            ]);
             //}else{ 
            $this->render($view, ['flag'=>$flag,'model' => $model
            ])*/
            ?>
            <?php //} ?>
        </div>
       
    </div>
</div>
