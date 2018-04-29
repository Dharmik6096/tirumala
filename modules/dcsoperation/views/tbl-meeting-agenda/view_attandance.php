<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'meeting attendance')).' => '.$model->subject_line;
?>
<div class="panel panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
        <div class="pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="false">
        <?= Html::a(Yii::t('app', 'Back To Meeting Agneda List'),['tbl-meeting-agenda/index'], ['class' => 'btn btn-default btn-create apply-shortcut']); ?>
    </div>
        </div>
    <div class="panel-body">
        <div class="table-responsive">
        <?php
        $attribute = [
            ['attribute' => 'member_code','value'=>'memberCode.member_name', 'vAlign' => 'middle',],
            ['attribute' => 'absent_reason','value'=>'absent_reason', 'vAlign' => 'middle',],
            [
                'attribute' => 'is_present',
                'vAlign' => 'middle',
                'filter' => Html::activeDropDownList($searchModel, 'is_present', [1=>'Yes',0=>'No'],['class'=>'form-control','prompt'=>'Select']),
                'value' => function($model) {

            return ($model->is_present==1)?'Yes':'No';}],
        ];

        $grid_option = [
            'id' => 'meeting-agenda-grid',
            'attributes' => $attribute,
            'active_column' => true,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option,['view-attandance','id'=> Yii::$app->request->get('id')]);
        ?>
        </div>
    </div>
</div>