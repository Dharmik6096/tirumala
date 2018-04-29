<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffAttendance */

$this->title = $model->staffMemberCode->staff_member_name;
?>
<div class="tbl-staff-attendance-view">
 <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lwp_date',
            'lwp_type',
            'remark',
            'salary_processed',
            'staffMemberCode.staff_member_name',
            'dcsCode.dcs_name',
            'subCenterCode.sub_center_name',
            'unionCode.union_name',
        ],
    ]) ?>
 </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?php echo Html::a('cancel', ['index'], ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>

        </div>
    </div>
</div>
