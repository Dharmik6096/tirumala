<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\usermanagement\models\User;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$title = Yii::$app->label->title('edit', 'user organization');
$button = Yii::$app->label->button('edit');

$this->title = Yii::t('app', $title);
?>
<div class="user-create">
    <div class="panel panel-main">
        <div class="panel-heading"><?php echo Yii::t('app', 'User-Organization Mapping'); ?></div>
         <?php
        $form = ActiveForm::begin([
                    'id' => 'user-org',
                    'validateOnBlur' => false,
                ]);
        ?>
        <div class="panel-body">
            <div class="panel-subheading">
                <h5 class="panel-subtitle"><?php echo $title; ?></h5>
                <?php echo $form->errorSummary($model); ?>
                <div class="row">
                    <?php
                    echo $form->field($model, 'user_type_id', [ 'options' => ['class' => 'form-group col-sm-3',]])
                            ->dropDownList(User::getUserTypeList(), ['prompt' => 'Select Organizations','class'=>'form-control disabled'])
                ?>

                    <?php echo $form->field($model, 'organizations', [ 'options' => ['class' => 'form-group col-sm-6',]])            
                        ->dropDownList($values,
                        [
                         'multiple'=>'multiple',
                         'class'=>'form-control',              
                        ]             
                       )->label("Organizations");     ?>
                    
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
                </div>        
            </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>