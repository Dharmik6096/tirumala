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
            <div id="provisional-member">
                <?php
                $action = Url::to(['provisional-members-approvals']);
                $form = ActiveForm::begin([
                            'id' => 'summary-form',
                            'action' => $action,
                            'method' => 'post']);
                ?>
                <div id="approval-form">
                    <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
                    <?php
                    $attr = [
                            ['attribute' => 'society_code', 'value' => 'dcs_code'],
                            ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
                            ['attribute' => 'pro_ex_member_code', 'value' => 'pro_ex_member_code', 'filter' => false],
                            ['attribute' => 'ex_member_code', 'value' => 'ex_member_code', 'filter' => false],
                            ['attribute' => 'member_code', 'value' => 'member_code', 'filter' => false],
                            ['attribute' => 'member_name', 'value' => 'member_name', 'filter' => false],
                            ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
                            ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
                            ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
                            ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
                            ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
                            ['attribute' => 'mobile_no', 'visible' => TRUE, 'filter' => false],
                            ['attribute' => 'ifsc', 'filter' => false],
                            ['attribute' => 'bank_account_no', 'filter' => false],
                    ];
                    $grid_option = [
                        'id' => 'milk-coll-dcs-list',
                        'class' => '',
                        'attributes' => $attr,
                        'active_column' => false,
                        'actions' => [
                            'milk_collection' => function ($url, $model) {
                                $class = ($model->is_approved == 1) ? 'link-disable' : '';
                                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Milk Collection', 'class' => 'view_data ' . $class, 'data-dcs_code' => $model->dcs_code, 'data-pro_ex_mem_code' => $model->pro_ex_member_code];
                                return GhostHtml::a_alert('<i class="fa fa-list"></i>', ['/dcsoperation/tbl-member-provisional/provisional-milk-collection-list'], $options);
                            },
                            'views' => function($url, $model) {
                                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Approve Member'];
                                return Html::a('<i class="fa fa-check"></i>', ['/dcsoperation/tbl-member-provisional/view', 'id' => $model->provisional_member_code, 'flag' => 'approve'], $options);
                            },
                        ]
                    ];
                    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['provisional-members-approval'], true);
                    ?>
                </div>
                <div class="clearfix">
                    <?= '</br>' ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div id="approvalDetails"></div>
<div id="milkCollectionDetails"></div>
<?php
$script = "

$(document).ready(function() {
    $('.btn-toolbar.kv-grid-toolbar').hide();
});

$(document).ready(function(){
    $(document).on('click','.view_data',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        var dcs_code= $(this).attr('data-dcs_code');
        var pro_ex_mem_code= $(this).attr('data-pro_ex_mem_code');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/dcsoperation/tbl-member-provisional/provisional-milk-collection-list']) . "',
            data:{'member_code':dcs_code+pro_ex_mem_code},
            success: function(data) {     
                $('#milkCollectionDetails').html(data);
                $('#provisionalMilkCollection').modal('toggle'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });
    });

";
$this->registerJs($script, View::POS_END, 'save-approval-data');
?>