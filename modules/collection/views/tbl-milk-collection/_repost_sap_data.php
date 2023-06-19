<?php

use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'SAP Data Repost');
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body ">
            <?php
            $sap_collection_type = $model->sap_collection_type;
            echo $this->render('_search_repost_sap_data', ['model' => $model]);
            ?>
            <?php
            $action = Url::to(['get-temp-data']);
            $form = ActiveForm::begin([
                        'id' => 'sap-data-repost-form',
//                        'action' => $action,
                        'method' => 'post']);
            ?>
            <?= Html::hiddenInput('flag', $model->sap_collection_type, ['id' => 'flag']); ?>
            <?php
            $attr = [
                    ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
//                $status = isset($model['is_approved']) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model['is_approved']] : '';
//                $status = !empty($status) && $status == 'Pending' ? false : true;
                        return ['value' => $model['data_post_id']];
                    }],
//                ['attribute' => 'dcs', 'label' => Yii::t('app', 'DCS'), 'filter' => false],
                ['attribute' => 'mcc_name', 'filter' => false],
                    ['attribute' => 'society_code', 'filter' => false],
                    ['attribute' => 'dcs_name', 'filter' => false],
                    ['attribute' => 'member_code', 'filter' => false],
                    ['attribute' => 'member_name', 'filter' => false],
                    ['attribute' => 'collection_date', 'filter' => false],
                    ['attribute' => 'shift', 'filter' => false],
                    ['attribute' => 'sample_no', 'filter' => false],
                    ['attribute' => 'fat', 'filter' => false],
                    ['attribute' => 'snf', 'filter' => false],
                    ['attribute' => 'quantity', 'filter' => false],
                    ['attribute' => 'sap_status', 'filter' => false],
                    ['attribute' => 'response_description', 'filter' => false],
//                ['attribute' => 'is_approved', 'value' => function ($model) {
//                        return isset($model['is_approved']) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model['is_approved']] : '';
//                    }, 'filter' => false],
//                ['attribute' => 'is_updated', 'value' => function ($model) {
//                        return isset($model['is_updated']) ? Yii::$app->dropdown->getRecords('update_status')['data'][$model['is_updated']] : '';
//                    }, 'filter' => false],
            ];
            $grid_option = [
                'id' => 'sap-post-data-list',
                'attributes' => $attr,
                'active_column' => false,
            ];
            ?>
            <div class="col-sm-12">
                <?php
                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['repost-sap-data'], true);
                ?>
            </div>
            <?php
            if (!empty($dataProvider->getModels())) {
                ?>
                <div class="col-lg-12" >
                    <?= Html::submitButton(Yii::t('app', 'Repost'), ['class' => 'btn btn-default sap-data-repost', 'name' => 'approve']); ?>
                    <?php // Html::submitButton(Yii::t('app', 'Reject'), ['class' => 'btn btn-default milk-coll-submit', 'name' => 'reject']);  ?>
                </div>
            <?php } ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<!--<div id="milkCollectionDetails"></div>-->
<?php
$script = "
    $('.sap-data-repost').on('click',function(){
       $('form#sap-data-repost-form').submit();
       $('#pageloader').show();
       $('#loadercontent').show();
    });
    ";
$this->registerJs($script, View::POS_END, 'sap-data-repost');
?>