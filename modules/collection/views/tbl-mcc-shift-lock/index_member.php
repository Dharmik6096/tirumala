<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'MCC Shift Lock (Member)'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <!--                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                                    <h4 class="theme-box-heading">MCC Shift Lock (Member)</h4>
                                </div>-->
                <div class="large-search hidden-print">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                    <div class="clearfix"></div>
                    <?=
                    $this->render('_form_grid_member', [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

