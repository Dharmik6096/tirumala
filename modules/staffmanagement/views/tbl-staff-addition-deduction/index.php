<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\TblStaffAdditionDeductionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Staff Addition Deductions');
?>
<div class="tbl-staff-addition-deduction-index">
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
                    ['attribute' => 'staff_member_name', 'header' => Yii::t('app', 'Staff Member Code'), 'value' => 'staffMemberCode.staff_member_code'],
                    ['attribute' => 'staff_member_code', 'header' => Yii::t('app', 'Staff Member Name'), 'value' => 'staffMemberCode.staff_member_name'],
                    ['attribute' => 'app_from_date', 'header' => Yii::t('app', 'Date'), 'value' => 'app_from_date'],
                ];

                $grid_option = [
                    'id' => 'staff-attendances-list',
                    'attributes' => $attribute,
                    'active_column' => true,
                    'actions' => [
                        'view' => TRUE,
                        'delete' => ['option' => 'staff_member_code,tr_no,staffmanagement/tbl-staff-addition-deduction/delete'],
                    ]
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
             </div>
        </div>
    </div>   
            </div>
