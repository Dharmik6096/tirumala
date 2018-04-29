<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;
/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffMember */

$this->title = $model->staff_member_code;
?>
<div class="tbl-asset-view">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">
        
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table detail-view'],
        'attributes' => [
            'staff_member_code',
            'aadhar_card_no',
            'address',
            'bank_account_no',
            'birth_date',
            'email_id',
            'ifsc',
            'mobile_no',
            'pan_no',
            'payment_mode',
            'pincode',
            'staff_member_name',
            'tenure_from_date',
            'tenure_to_date',
            'bankCode.bank_name',
            'branchCode.branch_name',
            'bloodGroup.blood_group',
            'casteCategoryCode.caste_category_name',
            'designationCode.designation_name',
            'gender.gender',
            'memberCode.member_name',
            'stateCode.state_name',
            'districtCode.district_name',
            'subDistrictCode.sub_district_name',
            'villageCode.village_name',
            'hamletCode.hamlet_name',
            'subCenterCode.sub_center_name',
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
