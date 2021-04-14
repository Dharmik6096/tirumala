<?php

use yii\helpers\Url;

$yesterday = date('Y-m-d', strtotime('-1 day', strtotime($date)));
?>

<div class="col-sm-12 today_vs_yesterday_collection">
    <div class="col-sm-6">
        <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Today Procurement (') . Yii::$app->controls->view_date($date) . ')'; ?></div>
        <?php $url = Url::to(['site/get-unions', 'date' => $date, 'union_code' => $model->union_code]); ?>
        <a href="<?= $url ?>" target="_blank">
            <div class="link_hover_effect">
                <div class="div_dash_block width_30-10  height_115 dashboardWidgetDetailPortion <?= $class_cols ?>">
                    <div class="">
                        <p class="dash_block_header"><?= Yii::t('app', 'Milk Collection') ?></p>
                        <table class="width_100 dashboard_widget_table">
                            <tr>
                                <td><?= Yii::t('app', 'CC QTY. : ') ?>
                                    <span id="today_cc_qty"></span>
                                </td>
                            </tr>
                            <tr>
                                <td><?= Yii::t('app', 'CC COUNT : ') ?>
                                    <span id="today_cc_count"></span></td>
                            </tr>
                            <tr>
                                <td><?= Yii::t('app', 'AVG RATE : ') ?>
                                    <span id="today_avg_rate"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </a>

        <?php $url = Url::to(['site/get-rmrd-unions', 'date' => $date, 'union_code' => $model->union_code]); ?>
        <a href="<?= $url ?>" target="_blank">
            <div class="div_dash_block width_70-10 height_115 dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display ?>">
                <div class="">
                    <p class="dash_block_header"><?= Yii::t('app', 'BMC Collection') ?></p>
                    <table class="width_100 dashboard_widget_table">
                        <tr>
                            <td><?= Yii::t('app', 'TOTAL LTRs. : ') ?>
                                <span id="today_total_ltr"></span>
                            </td>
                            <td><?= Yii::t('app', 'AVG RATE : ') ?>
                                <span id="today_rmrd_avg_rate"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'CC COUNT : ') ?>
                                <span id="today_rmrd_cc_count"></span></td>
                            <td><?= Yii::t('app', 'CC QTY : ') ?>
                                <span id="today_rmrd_cc_qty"></span></td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'BULK AG. COUNT : ') ?>
                                <span id="today_bulk_count"></span></td>
                            <td><?= Yii::t('app', 'BULK AG. QTY : ') ?>
                                <span id="today_bulk_qty"></span></td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'VLCC VENDOR COUNT : ') ?>
                                <span id="today_vlcc_count"></span></td>
                            <td><?= Yii::t('app', 'VLCC VENDOR QTY : ') ?>
                                <span id="today_vlcc_qty"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6">
        <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Yesterday Procurement (') . Yii::$app->controls->view_date($yesterday) . ')'; ?></div>
        <?php $url = Url::to(['site/get-unions', 'date' => $yesterday, 'union_code' => $model->union_code]); ?>
        <a href="<?= $url ?>" target="_blank">
            <div class="div_dash_block width_30-10  height_115 dashboardWidgetDetailPortion <?= $class_cols ?>">
                <div class="">
                    <p class="dash_block_header"><?= Yii::t('app', 'Milk Collection') ?></p>
                    <table class="width_100 dashboard_widget_table">
                        <tr>
                            <td><?= Yii::t('app', 'CC QTY. : ') ?>
                                <span id="yesterday_cc_qty"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'CC COUNT : ') ?>
                                <span id="yesterday_cc_count"></span></td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'AVG RATE : ') ?>
                                <span id="yesterday_avg_rate"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </a>

        <?php $url = Url::to(['site/get-rmrd-unions', 'date' => $yesterday, 'union_code' => $model->union_code]); ?>
        <a href="<?= $url ?>" target="_blank">
            <div class="div_dash_block width_70-10 height_115 dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display ?>">
                <div class="">
                    <p class="dash_block_header"><?= Yii::t('app', 'BMC Collection') ?></p>
                    <table class="width_100 dashboard_widget_table">
                        <tr>
                            <td><?= Yii::t('app', 'TOTAL LTRs. : ') ?>
                                <span id="yesterday_total_ltr"></span>
                            </td>
                            <td><?= Yii::t('app', 'AVG RATE : ') ?>
                                <span id="yesterday_rmrd_avg_rate"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'CC COUNT : ') ?>
                                <span id="yesterday_rmrd_cc_count"></span></td>
                            <td><?= Yii::t('app', 'CC QTY : ') ?>
                                <span id="yesterday_rmrd_cc_qty"></span></td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'BULK AG. COUNT : ') ?>
                                <span id="yesterday_bulk_count"></span></td>
                            <td><?= Yii::t('app', 'BULK AG. QTY : ') ?>
                                <span id="yesterday_bulk_qty"></span></td>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'VLCC VENDOR COUNT : ') ?>
                                <span id="yesterday_vlcc_count"></span></td>
                            <td><?= Yii::t('app', 'VLCC VENDOR QTY : ') ?>
                                <span id="yesterday_vlcc_qty"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </a>
    </div>
</div>