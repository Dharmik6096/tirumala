<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Tbl Union Bank Payments');
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu'][] = Html::a('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add ' . ucfirst(' Union Bank Detail')), ['create'], ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>

    <div class="panel-body">
        <div class="table-responsive">
            <?php Pjax::begin(); ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    // 'union_bank_payment_code',
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
                    // 'created_at',
                    // 'created_by',
                    // 'updated_at',
                    // 'updated_by',
                    'mobile_no',
                    'email:email',
                    // 'ftp_type',
                    // 'ftp_server',
                    // 'ftp_username',
                    // 'ftp_password',
                    // 'ftp_port',
                    // 'reverse_ftp_path',
                    // 'reverse_server_path',
                    // 'compare_file_name',
                    'bank_email:email',
                    'bank_mobile',
                    'corporate_code',
                    'integration_mode',
                    'is_active',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete} {duplicate} {debit-bank}', // Added debit-bank action
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-eye"></span>', $url, [
                                            'title' => Yii::t('app', 'View'),
                                            'class' => 'btn btn-info btn-xs',
                                ]);
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-pencil"></span>', $url, [
                                            'title' => Yii::t('app', 'Update'),
                                            'class' => 'btn btn-primary btn-xs',
                                ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-trash"></span>', $url, [
                                            'title' => Yii::t('app', 'Delete'),
                                            'class' => 'btn btn-danger btn-xs',
                                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'data-method' => 'post',
                                ]);
                            },
                            'debit-bank' => function ($url, $model) {
                                $options = [
                                    'data-code' => $model->union_bank_payment_code,
                                    'data-toggle' => 'tooltip',
                                    'data-placement' => 'top',
                                    'data-original-title' => Yii::t('app', 'Debit bank Detail'),
                                    'class' => 'btn btn-success btn-xs'
                                ];
                                return Html::a('<i class="fa fa-plus"></i>', ['/payment/tbl-debit-bank-detail/create', 'id' => $model->union_bank_payment_code], $options);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
<?php Pjax::end(); ?>
        </div>
    </div>
</div>
