<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;


$this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Member Approval'));
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
        <div class="grid-search large-search">
            <?php echo $this->render('_search', ['model' => $model]); ?>
        </div>
            <div id="provisional-member">
            <?php
            $action = Url::to(['provisional-members-approval']);
            $form = ActiveForm::begin([
                        'id' => 'summary-form',
                        'action' => $action,
                        'method' => 'post']);
            ?>
            <div id="approval-form">
            <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
            <?php
            $attr = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                    return ['value' => $model['provisional_member_code']];
                }],
                // ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
                ['attribute' => 'society_code', 'value' => 'dcs_code'],
                ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
                ['attribute' => 'pro_ex_member_code', 'value' => 'pro_ex_member_code', 'filter' => false],
                ['attribute' => 'ex_member_code', 'value' => 'ex_member_code', 'filter' => false],
                ['attribute' => 'member_code', 'value' => 'member_code', 'filter' => false],
                ['attribute' => 'member_name', 'value' => 'member_name', 'filter' => false],
                
                ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false,'filter' => false],
                ['attribute' => 'district_code', 'value' => 'districtCode.district_name','visible' => false, 'filter' => false],
                ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name','visible' => false, 'filter' => false],
                ['attribute' => 'village_code', 'value' => 'villageCode.village_name','visible' => false, 'filter' => false],
                ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name','visible' => false, 'filter' => false],
                ['attribute' => 'mobile_no', 'visible' => TRUE, 'filter' => false],
                ['attribute' => 'ifsc', 'filter' => false],
                ['attribute' => 'bank_account_no', 'filter' => false],
            ];
            $grid_option = [
                'id' => 'milk-coll-dcs-list',
                'class' => '',
                'attributes' => $attr,
                'active_column' => false,
                    ];
                    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['provisional-members-approval'], true);
                    ?>
            <?php //Html::submitButton(Yii::t('app', 'Reject'), ['class' => 'btn btn-default provisional-member-submit', 'name' => 'reject']); ?>
                    
            </div>
            <div class="clearfix">
                    <?= '</br>'?>
            </div>
                <?php
                    if($dataProvider->getCount() > 0){
                        echo Html::submitButton(Yii::t('app', 'Approve'), ['class' => 'btn btn-default provisional-member-submit', 'name' => 'approve']);
                    }
                ?>
                <?= Yii::$app->controls->custombutton('Cancle', '/dcsoperation/tbl-member-provisional/index');?>
             <?php ActiveForm::end(); ?>
            </div>
                </div>
            </div>
        </div>
<div id="approvalDetails"></div>
<?php
$script = "
$('.provisional-member-submit').on('click',function(){
    $('#flag').val($(this).prop('name'));
    $('form#summary-form').submit();
    $('#pageloader').show();
    $('#loadercontent').show();
});

$(document).ready(function() {
    $('.btn-toolbar.kv-grid-toolbar').hide();
});

";
$this->registerJs($script, View::POS_END, 'save-approval-data');
?>