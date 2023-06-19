<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Roll Back Farmer');
//$action = Url::to(['rollback-bulk']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        </div>
        <div class="clearfix"></div>
        <div class="grid-search no-effect" >
            <?php
            $form = ActiveForm::begin([
                        'id' => 'rollback-member',
                            //   'action' => $action,
            ]);
            ?>

            <?php
            $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                return ['class' => 'checkbox', 'value' => $model['member_code'] . '-' . $model['old_member_code']];
            }],
                ['attribute' => 'dcs_code',
                    'label' => Yii::t('app', 'DCS Code'),
                    'filter' => false],
                ['attribute' => 'dcs_code',
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    }, 'filter' => false],
                [ 'attribute' => 'member_code', 'value' => function($model) {
                        return substr($model->member_code, -4);
                    }, 'filter' => false],
                ['attribute' => 'vendor_code', 'filter' => false],
                ['attribute' => 'member_name', 'filter' => false],
                ['attribute' => 'old_dcs_code', 'filter' => false],
                ['attribute' => 'old_member_code','value' => function($model) {
                        return substr($model->old_member_code, -4);
                    }, 'filter' => false],
            ];

            $grid_option = [
                'id' => 'rollback-member-list',
                'attributes' => $attribute,
                'active_column' => false,
                'showPageSummary' => false,
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#']);
            ?>
            <div class="panel-footer" >
                <?php
                if (!empty($dataProvider->getModels())) {
                    echo Html::button(Yii::t('app', 'Roll Back'), ['class' => 'btn btn-primary', 'id' => 'rollback']);
                }
                ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#rollback").click(function() {
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Farmer.</span></div></div>");
                return false;
            } else {
            $("#rollback-member").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'rollback-member');
?>