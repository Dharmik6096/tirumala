<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\TblStaffSalaryProcessingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Staff Salary Processings');
?>
<div class="tbl-staff-salary-processing-index">
<div class="panel panel-main">
        <div class="panel-heading">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <div class="grid-search clearfix">
                    <?php echo $this->render('_search', ['model' => $searchModel]);  ?>
                </div>
                <?php
                $attribute = [
                    ['attribute' => 'staff_member_code', 'header' => Yii::t('app', 'Staff Member Code'), 'value' => 'staffMemberCode.staff_member_code'],
                    ['attribute' => 'staff_member_name', 'header' => Yii::t('app', 'Staff Member Name'), 'value' => 'staffMemberCode.staff_member_name'],
                    ['attribute' => 'disbursement_date', 'header' => Yii::t('app', 'Date'), 'value' => 'disbursement_date'],
                    ['attribute' => 'month', 'header' => Yii::t('app', 'Month'), 'value' => 'month'],
                    ['attribute' => 'value', 'header' => Yii::t('app', 'Amount'), 'value' => 'value'],
                    ['attribute' => 'lwp', 'header' => Yii::t('app', 'LWP'), 'value' => 'lwp'],
                    ['attribute' => 'bank_code', 'header' => Yii::t('app', 'Bank'), 'value' => 'bankCode.bank_name'],
                    
                ];

                $grid_option = [
                    'id' => 'staff-salary-processings-list',
                    'attributes' => $attribute,
                    'active_column' => true,
                    'actions' => [
                        'view' => TRUE,
                        'delete' => ['option' => 'staff_member_name,staff_member_code,staffmanagement/tbl-staff-salary-processing/delete'],
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
 <?php /*GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'month',
            'account_no',
            'created_at',
            'disbursement_date',
            'effective_working_days',
            // 'flg_sentbox_entry',
            // 'is_delete',
            // 'lwp',
            // 'sync_status',
            // 'sync_timestamp',
            // 'type_of_head',
            // 'updated_at',
            // 'value',
            // 'union_code',
            // 'sub_center_code',
            // 'staff_member_code',
            // 'designation_code',
            // 'dcs_code',
            // 'bank_code',
            // 'branch_code',
            // 'created_by',
            // 'salary_head_code',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);*/ ?>

            </div>
        </div>
    </div>
</div>
