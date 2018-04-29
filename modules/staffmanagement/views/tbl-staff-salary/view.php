<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;
/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalary */

$this->title = $model->staff_salary_code;
?>
<div class="tbl-staff-salary-view">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'staff_salary_code',
            'staffMemberCode.staff_member_name',
            'net_pay',
            'wef_date',
            'subCenterCode.sub_center_name',
            'dcsCode.dcs_name',
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
