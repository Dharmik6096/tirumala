<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\payment\models\TblDebitBankDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Debit Bank Details');
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu'][] = Html::a('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add ' . ucfirst(' Debit Bank Detail')), ['create'], ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'debit_bank_detail_code',
                    'union_bank_payment_code',
                    'union_code',
                    'module_code',
                    'module_name',
                    // 'branch_name',
                    // 'branch_code',
                    // 'ifsc',
                    // 'bank_account_no',
                    // 'account_holder_name',
                    // 'bank_email:email',
                    // 'bank_mobile',
                    // 'mobile_no',
                    // 'email:email',
                    // 'is_active',
                    // 'created_at',
                    // 'created_by',
                    // 'updated_at',
                    // 'updated_by',
                    // 'originating_org_code',
                    // 'originating_org_type',
                    // 'originating_type',
                    // 'x_col1',
                    // 'x_col2',
                    // 'x_col3',
                    // 'x_col4',
                    // 'x_col5',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
