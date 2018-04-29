<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\rmrd\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Logs'));
?>

<div class="log-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            
             <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            
             <?php

                $attribute = [
                    ['attribute' => 'Id'],
                    ['attribute' => 'Date',
                        'filterType' => GridView::FILTER_DATE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                                'autoclose' => FALSE]
                        ],
                        'value' => function($model) {
                        return Yii::$app->controls->view_date($model->Date);
                    }],
                    ['attribute' => 'Thread'],
                    ['attribute' => 'Level'],
                    ['attribute' => 'Logger'],
                    ['attribute' => 'Message', 'visible' => false],
                    ['attribute' => 'Exception', 'visible' => false],
                ];

                $grid_option = [
                    'id' => 'log-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'actions' => [
                        'view' => true,
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
    </div>
</div>