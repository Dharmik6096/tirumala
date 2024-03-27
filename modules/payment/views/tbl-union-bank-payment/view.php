<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblUnionBankPayment */

$this->title = $model->union_bank_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Union Bank Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-union-bank-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->union_bank_payment_code], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->union_bank_payment_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'union_bank_payment_code',
            'union_code',
            'bank_name',
            'bank_code',
            'branch_name',
            'branch_code',
            'ifsc',
            'bank_account_no',
            'account_holder_name',
            'file_path',
            'server_type',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'mobile_no',
            'email:email',
            'ftp_type',
            'ftp_server',
            'ftp_username',
            'ftp_password',
            'ftp_port',
            'reverse_ftp_path',
            'reverse_server_path',
            'compare_file_name',
            'bank_email:email',
            'bank_mobile',
            'corporate_code',
            'integration_mode',
            'is_active',
        ],
    ])
    ?>

</div>
