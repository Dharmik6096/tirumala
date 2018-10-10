<?php

use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collection Approve'));
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $model]); ?>
            <?php
            $action = Url::to(['get-temp-data']);
            $form = ActiveForm::begin([
                        'id' => 'summary-form',
                        'action' => $action,
                        'method' => 'post']);
            ?>

            <?php
            $attr = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                $status = isset($model['is_approved']) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model['is_approved']] : '';
                $status = !empty($status) && $status == 'Pending' ? false : true;
                return ['value' => $model['dcs_code'].'::'.$model['date_time_of_collection'].'::'.$model['shift_code'].'::'.$model['is_approved'].'::'.$model['is_updated'], 'disabled' => $status];
            }],
                ['attribute' => 'dcs', 'label' => Yii::t('app', 'DCS'), 'filter' => false],
                ['attribute' => 'date_time_of_collection', 'filter' => false],
                ['attribute' => 'shift', 'filter' => false],
                ['attribute' => 'is_approved', 'value' => function ($model) {
                        return isset($model['is_approved']) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model['is_approved']] : '';
                    }, 'filter' => false],
                ['attribute' => 'is_updated', 'value' => function ($model) {
                        return isset($model['is_updated']) ? Yii::$app->dropdown->getRecords('update_status')['data'][$model['is_updated']] : '';
                    }, 'filter' => false],
            ];
            $grid_option = [
                'id' => 'milk-coll-dcs-list',
                'attributes' => $attr,
                'active_column' => false,
            ];
            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['get-temp-data'], true);
            ?>
            <div class="col-lg-12" >
                <?= Html::submitButton(Yii::t('app', 'Approve'), ['class' => 'btn btn-default payment', 'name' => 'approve']); ?>
                <?= Html::submitButton(Yii::t('app', 'Reject'), ['class' => 'btn btn-default payment', 'name' => 'reject']); ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php
$script = "
    $('.payment').on('click',function(){
       $('form#summary-form').submit();
       $('#pageloader').show();
       $('#loadercontent').show();
    });
   
    ";
$this->registerJs($script, View::POS_END, 'save-coll-data');
?>