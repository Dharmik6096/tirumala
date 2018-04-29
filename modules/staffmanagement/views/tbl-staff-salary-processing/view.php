<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;
/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalaryProcessing */

$this->title = $model->month;
?>
<div class="tbl-staff-salary-processing-view">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table detail-view'],
        'attributes' => [
            'month',
            'account_no',
            'disbursement_date',
            'effective_working_days',
            'lwp',
            'type_of_head',
            'value',
            'unionCode.union_name',
            'subCenterCode.sub_center_name',
            'staffMemberCode.staff_member_code',
            'staffMemberCode.staff_member_name',
            'designationCode.designation_name',
            'dcsCode.dcs_name',
            'bankCode.bank_name',
            'branchCode.branch_name',
            'salaryHeadCode.salary_head_name',
            [
                'attribute' => 'is_active',
                'label' => 'Active',
                'format' => 'html',
                'value' => GeneralFunctions::getRecordStatus($model->is_active)
            ],
        ],
    ]) ?>

            </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?php echo Html::a('cancel', ['index'], ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
            
        </div>
    </div>
</div>
