<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

Url::remember();
$this->title = $title;
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>

    <div class="panel-body">
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Pending Request List</h4>
                </div>
                <div class="large-search hidden-print">
                    <?php echo $this->render('_search', ['searchModel' => $searchModel]); ?>
                </div>
                <div class="clearfix"></div>
                <div class="large-search hidden-print">
                    <?php
                    echo $this->render('block_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
