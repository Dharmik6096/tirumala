<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffAdditionDeduction */

$this->title = $model->tr_no;
?>
<div class="tbl-staff-addition-deduction-view">
   <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">
    <?= DetailView::widget([
        'model' => $model,
         'options' => ['class' => 'table detail-view'],
        'attributes' => [
            'tr_no',
            'app_from_date',
            'staffMemberCode.staff_member_code',
            'staffMemberCode.staff_member_name',
            'amount',
            'installment_no',
            'remark',
            'tr_date',
            'type',
            'dcsCode.dcs_name',
            'unionCode.union_name',
            'subCenterCode.sub_center_name',
        ],
    ]) ?>
          </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?php echo Html::a('cancel', ['index'], ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>

        </div>
    </div>
</div>
