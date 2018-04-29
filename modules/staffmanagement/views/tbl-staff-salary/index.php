<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\TblStaffSalarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Staff Salaries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-staff-salary-index">
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
   ['attribute' => 'staff_salary_code', 'header' => Yii::t('app', 'Staff Salary Code'), 'value' => 'staff_salary_code'],
   ['attribute' => 'dcs_code', 'header' => Yii::t('app', 'Dcs'), 'value' => 'dcsCode.dcs_name'],
   ['attribute' => 'sub_center_code', 'header' => Yii::t('app', 'Sub Center Name'), 'value' => 'subCenterCode.sub_center_name'],
   ['attribute' => 'staff_member_code', 'header' => Yii::t('app', 'Staff Member Name'), 'value' => 'staffMemberCode.staff_member_name'],
   ['attribute' => 'wef_date', 'header' => Yii::t('app', 'Wef Date'), 'value' => 'wef_date'],
   ['attribute' => 'net_pay', 'header' => Yii::t('app', 'Value'), 'value' => 'net_pay'],
];

$grid_option = [
                    'id' => 'staff-salary-list',
                    'attributes' => $attribute,
                    'active_column' => true,
                    'actions' => [
                        'view' => TRUE,
                        'delete' => ['option' => 'wef_date,staff_salary_code,staffmanagement/tbl-staff-salary/delete'],
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php Pjax::begin(); ?>    <?php /*GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'staff_salary_code',
            'created_at',
            'deleted_at',
            'flg_sentbox_entry',
            'is_active',
            // 'is_delete',
            // 'net_pay',
            // 'sync_status',
            // 'sync_timestamp',
            // 'updated_at',
            // 'wef_date',
            // 'created_by',
            // 'dcs_code',
            // 'deleted_by',
            // 'staff_member_code',
            // 'sub_center_code',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);*/ ?>
<?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
