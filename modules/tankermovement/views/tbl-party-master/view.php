<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblPartyMaster */

$this->title = $model->party_master_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Party Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-party-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->party_master_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->party_master_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'party_master_code',
            'union_code',
            'party_name',
            'party_contact_no',
            'party_address',
            'owner_name',
            'owner_contact_no',
            'owner_email:email',
            'owner_address',
            'state_code',
            'district_code',
            'sub_district_code',
            'village_code',
            'hamlet_code',
            'bank_code',
            'branch_code',
            'bank_account_no',
            'ifsc',
            'beneficiary_name',
            'pan_no',
            'adhar_no',
            'is_active',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
