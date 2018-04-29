<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\globalmaster\models\TblMilkQualityGrade */

$this->title = Yii::$app->label->title('view', 'Milk Quality Grade');

?>
<div class="tbl-milk-quality-grade-view">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table detail-view'],
        'attributes' => [
            'unionCode.union_name',
            'animalTypeCode.animal_type_name',
            'grade_name',
            'ded_percentage',
            'name',
            [
                'attribute' => 'is_active',
                'label' => 'Status',
                'format' => 'html',
                'value' => GeneralFunctions::getRecordStatus($model->is_active)
            ],
        ],
    ]) 
        ?>
        </div>
           <?php 
           $getId = $model->getId($model->animal_type_code,$model->union_code,$model->grade_name);
           $form = ActiveForm::begin(); ?> 
             <?=
            $this->render('_milk_type', [
                'milkType' => $milkType, 'modelMilk' => $modelMilk, 'form' => $form, 'model' => $model,'type'=>'view'
            ])
            ?>
           <?php ActiveForm::end(); ?>
            </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->update($getId); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
