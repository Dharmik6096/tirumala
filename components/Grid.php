<?php

namespace app\components;

use yii;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\dynagrid\DynaGrid;
use yii\base\Widget;
use kartik\export\ExportMenu;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Url;
use app\modules\verification\models\TblVerification;
use app\modules\verification\models\TblKycRecord;
use yii\web\View;

class Grid extends Widget {

    private $id;
    private $url;
    public $template = '{update}{delete}';

    public function run() {
        Url::remember();
        return $this->render('grid', ['id' => $this->id, 'url' => $this->url]);
    }

    public function bind($dataProvider, $searchModel, $grid_option, $refresh_action = ['index'], $filter = true, $removeExportType = [], $exportEvents = [], $fixed_header = true, $rowOptions = [], $ignoreDynagrid = false) {
        $table_name = '';
        if (!isset($searchModel->grid_filter) || $searchModel->grid_filter) {
            echo $this->render('@app/components/views/_search_filter', ['model' => $searchModel]);
            if (isset($searchModel->tableSchema->fullName)) {
                $table_name = $searchModel->tableSchema->fullName;
                if (Yii::$app->session->get('makerChecker') == 1) {
                    $grid_option = $this->getVerificationActions($table_name, $grid_option);
                }
                if (in_array($table_name, array('tbl_member'))) {
                    $kyc_model = new TblKycRecord();
                    echo $this->render('@app/modules/verification/views/verification/kyc_form', ['model' => $kyc_model, 'id' => $grid_option['id']]);
                }
            }
        }

        $module = Yii::$app->controller->module->id;
        if ((!isset($grid_option['default_sorting']) || $grid_option['default_sorting']) && isset($searchModel->tableSchema->primaryKey[0]) && !($dataProvider instanceof yii\data\ArrayDataProvider)) {
            $primary = $searchModel->tableSchema->primaryKey;
            $sort = [];
            $sort_type = in_array($module, ['geo', 'organisation', 'globalmaster']) ? SORT_ASC : SORT_DESC;
            foreach ($primary as $prm) {
                $sort[$prm] = $sort_type;
            }
            $select_array = $dataProvider->query->select;
            if (!empty($primary) && (empty($select_array) || in_array($primary[0], $select_array))) {
                $dataProvider->sort = ['defaultOrder' => $sort];
            }
        }

        //Start: check allow button for view history
        $viewHistory = false;
        if (!empty(Yii::$app->session->get('ViewHistory') && !empty($refresh_action[0]) && $refresh_action[0] == 'index')) {
            if (!empty($table_name)) {
                $excludes = [];
                $exclude = explode(',', Yii::$app->session->get('ViewHistory'));
                if (in_array($table_name, $exclude)) {
                    $viewHistory = TRUE;
                }
            }
        }//End: check allow button for view history

        $refresh_action = \Yii::$app->request->url;
        $grid_option = (Object) $grid_option;
        $this->id = $grid_option->id;
        Pjax::begin([
            'id' => $grid_option->id,
            'linkSelector' => false,
            'timeout' => false,
            'enablePushState' => true,
            'options' => ['class' => 'grid-content',]]);

        $columns = [
            ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT, 'mergeHeader' => false, 'headerOptions' => ['class' => 'seq-cell'], 'vAlign' => 'top'],
        ];

        
        if (isset($grid_option->actions)) {
            if ($viewHistory == true) { //allow view history
                $grid_option->actions['view-history'] = 1;
            }
            $keys = array_keys($grid_option->actions);

            $awidth = '150px';
            if (count($keys) > 3)
                $awidth = '200px';
            if (count($keys) > 4)
                $awidth = '250px';
            $template = implode(',', $keys);

            $template = '{' . str_replace(',', '}{', $template) . '}';
            $option = '';
            if (isset($grid_option->actions['delete']) && $grid_option->actions['delete'] != false) {
                $option = explode(',', $grid_option->actions['delete']['option']);
                $this->url = $option[2];
            }

            $action_column = ['class' => 'kartik\grid\ActionColumn',
                'width' => $awidth,
                'headerOptions' => ['class' => 'action-cell'],
                'contentOptions' => ['class' => 'action-cell'],
                'template' => $template,
                'dropdown' => false,
                'mergeHeader' => false,
                'order' => DynaGrid::ORDER_FIX_RIGHT,
                'buttons' => [
                    'view' => function ($url, $model)use ($grid_option) {
                        if (isset($grid_option->actions['view']) && $grid_option->actions['view'] !== FALSE) {
                            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View']);
                        }
                    },
                    'update' => function ($url, $model)use ($grid_option) {
                        if (isset($grid_option->actions['update']) && $grid_option->actions['update'] !== FALSE) {
                            if (is_callable($grid_option->actions['update'])) {
                                return $grid_option->actions['update']($url, $model);
                            }
                            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit']);
                        }
                    },
                    'delete' => function ($url, $model) use ($option, $grid_option) {
                        $baseurl = Yii::$app->request->baseUrl . '/';
                        $checkUrl = str_replace($baseurl, '', $url);
                        $checkUrl = explode('?', $checkUrl)[0];
                        $checkUrl = Yii::$app->general->base64url_decode($checkUrl);
                        if (isset($grid_option->actions['delete']) && $grid_option->actions['delete'] !== FALSE && User::canRoute($checkUrl)) {
                            $class = '';
                            if (isset($option[3])) {

                                $flag = true;
                                if (strpos($option[3], "()"))
                                    $flag = $model->{str_replace('()', '', $option[3])}();
                                else
                                    $flag = $model->{$option[3]};

                                if (!$flag)
                                    $class = 'link-disable';
                            }
                            $optionParts = explode('###', $option[0]);
                            $concatenatedName = '';
                            foreach ($optionParts as $part) {
                                $isSign = strpos($part, '~') !== false;
                                list($part, $format) = $isSign ? explode('~', $part) : [$part, ''];
                                $nestedAttribute = explode('.', $part);
                                $value = count($nestedAttribute) == 2 ? $model->{$nestedAttribute[0]}->{$nestedAttribute[1]} : $model->{$part};
                                switch ($format) {
                                    case 'date':
                                        $value = date('d-m-Y', strtotime($value));
                                        break;
                                }
                                $concatenatedName .= $value . ' > ';
                            }
                            $dataName = rtrim($concatenatedName, ' > ');

                            $options = ['class' => 'delete-record ' . $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'data-name' => $dataName, 'data-val' => $model->{$option[1]}];
                            return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0)', $options);
                        }
                    },
                    'view-history' => function ($url, $model)use ($grid_option, $table_name ) {
                        $callUrl['table_name'] = $table_name;
                        $primaryKey = $model->tableSchema->primaryKey;
                        foreach ($primaryKey as $primary) {
                            $id = $model->$primary;
                        }
                        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View History'), 'class' => 'view_history', 'data-table_name' => $table_name, 'data-id' => $id];
                        return GhostHtml::a_alert('<i class="fa fa-history"></i>', ['/misreports/reports/view-history'], $options);
                    },
                ]
            ];

            array_push($columns, $action_column);
            foreach ($grid_option->actions as $key => $a) {
                if (!($key == 'delete' || $key == 'view' || $key === 'update' || $key === 'view-history')) {
                    $columns[1]['buttons'][$key] = $a;
                }
            }
        }


        foreach ($grid_option->attributes as $g) {
            array_push($columns, $g);
        }
        //$export_column = $grid_option->attributes;
        foreach ($grid_option->attributes as $col) {
            $is_export = TRUE;
            if (is_array($col)) {
                if (array_key_exists('visible', $col)) {
                    $col['visible'] = true;
                }
                if (array_key_exists('hiddenFromExport', $col)) {
                    $is_export = FALSE;
                }
            }
            if ($is_export) {
                $export_column[] = $col;
            }
        }

        if ($grid_option->active_column) {
            $active_column = $this->activeColumn($searchModel);
            array_push($columns, $active_column);
            $export_column[] = $active_column;
        }
        $exportConfig = [
            ExportMenu::FORMAT_HTML => FALSE,
            ExportMenu::FORMAT_TEXT => FALSE,
            ExportMenu::FORMAT_PDF => FALSE,
            ExportMenu::FORMAT_EXCEL => FALSE,
            ExportMenu::FORMAT_EXCEL_X => [
                'label' => 'Excel',
            ]
        ];
        foreach ($removeExportType as $type) {
            $exportConfig[$type] = FALSE;
        }
        $ExportWidget = [
            'dataProvider' => $dataProvider,
            // 'template'=>'{menu}',
            'columns' => $export_column,
            'target' => ExportMenu::TARGET_BLANK,
            'filename' => empty($this->view->title) ? 'grid-export' : str_replace(' ', '-', $this->view->title),
            'clearBuffers' => TRUE,
            'fontAwesome' => true,
            'showColumnSelector' => true,
            'asDropdown' => true, // this is important for this case so we just need to get a HTML list
            'dropdownOptions' => [
                'label' => '<i class="glyphicon"></i>'
            ],
            'exportConfig' => $exportConfig,
            'batchSize' => 2000
        ];
        foreach ($exportEvents as $event => $content) {
            $ExportWidget[$event] = $content;
        }
        $fullExportMenu = ExportMenu::widget($ExportWidget);

        DynaGrid::begin([
            'columns' => $columns,
            'options' => ['id' => $grid_option->id],
            'theme' => 'simple-default',
            'showPersonalize' => true,
            'storage' => 'cookie',
            'showSort' => true,
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'rowOptions' => $rowOptions,
                'tableOptions' => array('class' => 'table table-bordered table-hover'),
                'filterModel' => $filter ? $searchModel : false,
                'showPageSummary' => !empty($grid_option->showPageSummary) ? $grid_option->showPageSummary : false,
//                        'floatHeader' => $fixed_header,
//                        'floatOverflowContainer' => $fixed_header,
                'pjax' => false,
                'panel' => ['heading' => false, 'before' => '',
                    'after' => '<div class="text-right padding-right-5">{pager}</div>',
                    'footer' => false],
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', $refresh_action, ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Refresh Grid'])
                    ],
                    ['content' => $ignoreDynagrid ? '' : '{dynagrid}'],
                    //  '{export}',
                    $fullExportMenu
                ],
            ]
        ]);
        DynaGrid::end();
        Pjax::end();
        $this->run();
    }

    private function activeColumn($searchModel) {
        return ['attribute' => 'is_active',
            'class' => 'kartik\grid\BooleanColumn',
            'header' => Yii::t('app', 'Status'), 'width' => '100px', //                    
            'trueLabel' => 'Active',
            'falseLabel' => 'In Active',
            'trueIcon' => '<span>Active</span>',
            'falseIcon' => '<span>In Active</span>',
        ];
    }

    private function getVerificationActions($table_name, $grid_option) {
        $bind_script = FALSE;
        $kyc_script = FALSE;
        $message = '';
//        if (in_array($table_name, array('tbl_bank_details', 'tbl_member'))) {
//            $bind_script = TRUE;
//            $grid_option['actions']['verify-bank-detail'] = function($url, $model) {
//                if ($model->is_active == 1 && !empty($model->bank_account_no)) {
//                    $flag = Yii::$app->controller->id;
//                    $verification = new TblVerification();
//                    $details = $verification->getBankDetails($flag);
//                    if ($details) {
//                        $checkdata = TblVerification::find()->where(['module_name' => $details['model'], 'module_id' => $model->{$details['field']}, 'module_field' => $details['verify_field']])->all();
//                        if (!$checkdata) {
//                            $bank = '';
//                            $branch = '';
//                            if ($model->bankCode) {
//                                $bank = $model->bankCode->bank_name;
//                            }
//                            if ($model->branchCode) {
//                                $branch = $model->branchCode->branch_name;
//                            }
//                            $name = !empty($model->member_name) ? '<br/> Member Name : ' . $model->member_name : '';
//                            $id = $flag . ',' . $model->{$details['field']};
//                            $VURL = Url::to(['/verification/verification/verify-bank-detail', 'id' => $id, 'type' => '1']);
//                            $RURL = Url::to(['/verification/verification/verify-bank-detail', 'id' => $id, 'type' => '2']);
//                            $message = '<div class = \"row\"><div class = \"bg-info\"><i class = \"fa fa-question\"></i></div><span>Are you sure you want to verify bank details ?' . $name . '<br/> Bank : ' . $bank . '<br/> Branch : ' . $branch . '<br/> Acc. No. : ' . $model->bank_account_no . '<br/> IFSC : ' . $model->ifsc . '</span></div>';
//                            $options = ['data-name' => $model->bank_account_no, 'data-val' => $id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Verify Bank Detail',
//                                'onClick' => 'js:VerifyAlert("' . $VURL . '","' . $RURL . '","' . $message . '");'];
//                            return GhostHtml::a('<i class="fa fa-bank"></i>', $VURL, $options);
//                        } else {
//                            if ($checkdata[0]->status == 1) {
//                                return GhostHtml::a('<i class="fa fa-bank text-success"></i>', ['/verification/verification/verify-bank-detail'], ['class' => 'link-disable', 'data-toggle' => 'tooltip',
//                                            'data-placement' => 'top',
//                                            'data-original-title' => 'Verified',
//                                ]);
//                            } else if ($checkdata[0]->status == 2) {
//                                return GhostHtml::a('<i class="fa fa-bank text-danger"></i>', ['/verification/verification/verify-bank-detail'], ['class' => 'link-disable', 'data-toggle' => 'tooltip',
//                                            'data-placement' => 'top',
//                                            'data-original-title' => 'Rejected',
//                                ]);
//                            }
//                        }
//                    }
//                }
//            };
//        }
//        if (in_array($table_name, array('tbl_contact_details', 'tbl_member'))) {
//            $bind_script = TRUE;
//            $grid_option['actions']['verify-contact-detail'] = function($url, $model) {
//                if ($model->is_active == 1 && !empty($model->mobile_no)) {
//                    $flag = Yii::$app->controller->id;
//                    $verification = new TblVerification();
//                    $details = $verification->getContactDetails($flag);
//                    if ($details) {
//                        $checkdata = TblVerification::find()->where(['module_name' => $details['model'], 'module_id' => $model->{$details['field']}, 'module_field' => $details['verify_field']])->all();
//                        if (!$checkdata) {
//                            $name = !empty($model->member_name) ? '<br/> Member Name : ' . $model->member_name : '';
//                            $id = $flag . ',' . $model->{$details['field']};
//                            $VURL = Url::to(['/verification/verification/verify-contact-detail', 'id' => $id, 'type' => '1']);
//                            $RURL = Url::to(['/verification/verification/verify-contact-detail', 'id' => $id, 'type' => '2']);
//                            $message = '<div class=\'row\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to verify Contact details ?' . $name . '<br/> Mobile No. : ' . $model->mobile_no . '</span></div>';
//                            $options = ['data-name' => $model->mobile_no, 'data-val' => $id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Verify Contact Detail',
//                                'onClick' => 'js:VerifyAlert("' . $VURL . '","' . $RURL . '","' . $message . '");'];
//                            return GhostHtml::a('<i class="fa fa-phone-square"></i>', $VURL, $options);
//                        } else {
//                            if ($checkdata[0]->status == 1) {
//                                return GhostHtml::a('<i class="fa fa-phone-square text-success"></i>', ['/verification/verification/verify-contact-detail'], ['class' => 'link-disable', 'data-toggle' => 'tooltip',
//                                            'data-placement' => 'top',
//                                            'data-original-title' => 'Verified',
//                                ]);
//                            } else if ($checkdata[0]->status == 2) {
//                                return GhostHtml::a('<i class="fa fa-phone-square text-danger"></i>', ['/verification/verification/verify-contact-detail'], ['class' => 'link-disable', 'data-toggle' => 'tooltip',
//                                            'data-placement' => 'top',
//                                            'data-original-title' => 'Rejected',
//                                ]);
//                            }
//                        }
//                    }
//                }
//            };
//        }
        if (in_array($table_name, array('tbl_member'))) {
            $grid_option['actions']['kyc-detail'] = function($url, $model) {
                if ($model->is_active == 1 && !empty($model->bank_account_no)) {
                    $flag = Yii::$app->controller->id;
                    $verification = new TblVerification();
                    $details = $verification->getBankDetails($flag);
                    if ($details) {
                        $checkdata = TblKycRecord::find()->where(['module_name' => $details['model'], 'module_id' => $model->{$details['field']}])->all();
                        if (!$checkdata) {
                            $KYCURL = Url::to(['/verification/verification/kyc-detail']);
                            $options = ['data-name' => $details['model'], 'data-val' => $model->{$details['field']}, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'KYC Detail', 'class' => 'kyc-modal'];
                            return GhostHtml::a_alert('<i class="fa fa-id-badge"></i>', $KYCURL, $options);
                        } else {
                            return GhostHtml::a('<i class="fa fa-id-badge text-success"></i>', ['/verification/verification/kyc-detail'], ['class' => 'link-disable', 'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                            ]);
                        }
                    }
                }
            };
        }
        if ($bind_script) {
            $script = "
                function VerifyAlert(vurl,rurl,message){
                    event.preventDefault();
                    bootbox.dialog({
                        message: message,         
                        buttons: {
                            cancel: {
                                label: 'Cancel',
                                className: 'btn-danger',
                                callback: function(){          
                                }
                            },
                            confirm: {
                                label: 'Verify',
                                className: 'btn-primary',
                                callback: function(){  
                                    window.location = vurl;
                                }
                            },
                            reject: {
                                label: 'Reject',
                                className: 'btn-danger',
                                callback: function(){  
                                    window.location = rurl;
                                }
                            }
                        }             
                    });  
                }
            ";
            Yii::$app->view->registerJs($script, View::POS_END, 'verify-data');
        }
        return $grid_option;
    }

}

?>