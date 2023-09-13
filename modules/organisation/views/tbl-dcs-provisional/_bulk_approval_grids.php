<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Dcs Provisional Approval'));
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div id="provisional-member">
                <?php
                $action = Url::to(['dcs-provisional-approval']);
                $form = ActiveForm::begin([
                            'id' => 'summary-form',
                            'action' => $action,
                            'method' => 'post']);
                ?>
                <div id="approval-form">
                    <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
                    <?php
                    $attr = [
                            ['attribute' => 'dcs_provisional_code', 'value' => 'dcs_provisional_code'],
//                            ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
//                            ['attribute' => 'pro_ex_member_code', 'value' => 'pro_ex_member_code', 'filter' => false],
                            ['attribute' => 'dcs_code_ex', 'value' => 'dcs_code_ex', 'filter' => false],
//                            ['attribute' => 'member_code', 'value' => 'member_code', 'filter' => false],
                            ['attribute' => 'dcs_name', 'value' => 'dcs_name', 'filter' => false],
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
                            'views' => function($url, $model) {
                                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Approve Member'];
                                return Html::a('<i class="fa fa-check"></i>', ['/organisation/tbl-dcs-provisional/view', 'id' => $model->dcs_provisional_code, 'flag' => 'approve'], $options);
                            },
                        ]
                    ];
                    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['dcs-provisional-approval'], true);
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
<!--<div id="approvalDetails"></div>
<div id="milkCollectionDetails"></div>-->