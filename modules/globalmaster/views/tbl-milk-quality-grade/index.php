<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\globalmaster\models\TblMilkQualityGradeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Quality Grade'));
?>
<div class="tbl-milk-quality-grade-index">

    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
            <div class="dropdown pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">
                <a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle btn btn-fab btn-fab-mini"><i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu">
                    <li><?= Yii::$app->controls->add('Milk Quality Grade'); ?></li>
                    <li><?php //echo Html::a(Yii::t('app', 'Import Tax'), 'javascript:void(0)', ['class' => 'btn btn-default btn-create apply-shortcut', 'id' => 'import-file', 'shortcut_key' => 'ctrl+alt+i']);     ?></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <div class="grid-search clearfix">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>

                <?php
                $attribute = [
                    ['attribute' => 'grade_name', 'value' => 'grade_name', 'vAlign' => 'middle',],
//                    ['attribute' => 'ded_percentage', 'value' => 'ded_percentage', 'vAlign' => 'middle',],
                    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'vAlign' => 'middle',],
                    ['attribute' => 'animal_type_code', 'value' => 'animalTypeCode.animal_type_name', 'vAlign' => 'middle',],
                ];

                $grid_option = [
                    'id' => 'milk-grade-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'actions' => [
                        'view_page' => function ($url, $model) {
                            $options = ['title' => 'View'];
                            $getId = $model->getId($model->animal_type_code, $model->union_code, $model->grade_name);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/globalmaster/tbl-milk-quality-grade/view', 'id' => $getId], $options);
                        },
                                'edit' => function ($url, $model) {
                            $options = ['title' => 'Update'];
                            $getId = $model->getId($model->animal_type_code, $model->union_code, $model->grade_name);
                            return Html::a('<span class="fa fa-pencil-alt"></span>', ['/globalmaster/tbl-milk-quality-grade/update', 'id' => $getId], $options);
                        },
                                'delete' => ['option' => 'grade_name,grade_code,globalmaster/tbl-milk-quality-grade/delete'],
                            ]
                        ];

                        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                        ?>
            </div>
        </div>
    </div>
</div>
