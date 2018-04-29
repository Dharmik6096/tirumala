<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\TblStaffMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Staff Members');
?>
<div class="tbl-staff-member-index">
<div class="panel panel-main">
        <div class="panel-heading">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <div class="grid-search clearfix">
                    <?php echo $this->render('_search', ['model' => $searchModel]);   ?>
                </div>
<?php
$attribute = [
    ['attribute' => 'staff_member_code', 'header' => Yii::t('app', 'Staff Member Code'), 'value' => 'staff_member_code'],
    ['attribute' => 'staff_member_name', 'header' => Yii::t('app', 'Staff Member Name'), 'value' => 'staff_member_name'],
    ['attribute' => 'designation_code', 'header' => Yii::t('app', 'Designation'), 'value' => 'designationCode.designation_name'],
    ['attribute' => 'tenure_from_date', 'header' => Yii::t('app', 'Date From'), 'value' => 'tenure_from_date'],
    ['attribute' => 'tenure_to_date', 'header' => Yii::t('app', 'Date To'), 'value' => 'tenure_to_date'],
        /* ['attribute' => 'local_name','header' => Yii::t('app', 'Local Name'),
          'hidden'=>Yii::$app->session->get('organizations_type')=='Nationals'?true:false,
          'contentOptions' => function ($model, $key, $index, $column) {
          return ['class' => Yii::$app->session->get('FontName')];},
          'filter' => Html::activeTextInput($searchModel, 'local_name', ['class' => 'form-control ' . Yii::$app->session->get('FontName')]),
          'value' => function($model) {
          $localName = Yii::$app->general->getLocalName('TblStaffMemberLocal', 'staff_member_code', $model->staff_member_code, Yii::$app->session->get('LanguageId'));
          return $localName;}], */
];
    $grid_option = [
                    'id' => 'staff-member-list',
                    'attributes' => $attribute,
                    'active_column' => true,
                    'actions' => [
                        'view' => TRUE,
                        'delete' => ['option' => 'staff_member_name,staff_member_code,staffmanagement/tbl-staff-member/delete'],
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option); ?>

            </div>
        </div>
    </div>
            </div>
