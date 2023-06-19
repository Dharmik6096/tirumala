<?php

namespace app\modules\translation\controllers;

use Yii;
use app\controllers\ChildController;
use yii\helpers\Json;
use yii\helpers\Url;
use PHPExcel;

/**
 * Default controller for the `translation` module
 */
class DefaultController extends ChildController {

    /**
     * Renders the index view for the module
     * @return string
     */
    private $_action;

    public function actionIndex($l, $flag = '', $type = '') {
        // Url::remember();
        $this->layout = "@app/web/themes/nddb/layouts/dashboardLayout.php";
        $model = $this->languageModel($l);

        $searchModel = null;
        $dataProvider = $model['left_column'];
        if (isset($model['searchModel'])) {
            $searchModel = $model['searchModel'];

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->models;
        }
        $modelLangauge = $this->getLanguages();

        if (Yii::$app->getRequest()->getQueryParam('flag') == 'export')
            $this->exportTranslations($model, $dataProvider, $modelLangauge, Yii::$app->getRequest()->getQueryParam('type'),'',Yii::$app->getRequest()->getQueryParam('local_fields'));

//        if ($model['multiple'] != '') {
//            $model['language_local']->addError('local_name', 'Go to multiple translation interface');
//            return $this->render('index', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'modelLangauge' => $modelLangauge]);
//        }
        if (Yii::$app->request->post()) {

            $fields = explode(',', $model['data']['fields']);
            if (isset($_POST[$model['data']['post']])) {
                $this->_action = $model['action'];
                $post = $_POST[$model['data']['post']];

                $list = [];
//                echo '<pre>';
//                print_r($_POST);
//                exit;
                $orgCode = Yii::$app->session->get('organizations_code');

                $localModelName = Yii::$app->path->define($model['data']['post']);
                $localModel = new $localModelName();
                $code = Yii::$app->general->getLocalCode($localModel->tableName());
                $incrNo = substr($code, (strlen($idenRecord->organization_code)+1));

                foreach ($post as $row) {
                    foreach ($row as $c) {
                        if (!empty($c[$_POST['field']])) {
                            $modelname = Yii::$app->path->define($model['data']['post']);
                            $model[1] = new $modelname();
                            if (strlen($c[$_POST['field']]) == mb_strlen($c[$_POST['field']], 'UTF-8')) {
                                $model['language_local']->addError('local_name', 'Data Should be in UTF-8 Format');
                                return $this->render('index', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'modelLangauge' => $modelLangauge]);
                            }
                            if (empty($c['id'])) {
                                $model[1]->local_code = $orgCode.'-'.$incrNo;
                                $model[1]->is_active = 1;
                                $model[1]->{$_POST['field']} = $c[$_POST['field']];
                                $model[1]->language_code = $c['language_code'];
                                $model[1]->{$fields[0]} = $c[$fields[0]];
                                $list[] = $model[1];
                                $incrNo=$incrNo+1;
                            } else {
                                $model[1] = $model[1]::findOne($c['id']);
                                if ($model[1]->{$_POST['field']} !== $c[$_POST['field']]) {
                                    $history = Yii::$app->path->define($model['data']['post']) . 'History';
                                    $historyModel = new $history();
                                    Yii::$app->operation->history($model[1], $historyModel, UPDATE);
                                    $model[1]->is_active = 1;
                                    $model[1]->{$_POST['field']} = $c[$_POST['field']];
                                    $model[1]->language_code = $c['language_code'];
                                    $model[1]->{$fields[0]} = $c[$fields[0]];
                                    $list[] = $model[1];
                                    $list[] = $historyModel;
                                }
                            }
                        }
                    }
                }
                $transaction = $this->generalModel->saveTransaction($list, ['language translation', 'edit']);
                if ($transaction !== FALSE) {
                    if($transaction=='customRender')
                        return $this->render('index', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'modelLangauge' => $modelLangauge]);

                    return $this->{$transaction}();
                }
            }
//            else
            return $this->render('index', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'modelLangauge' => $modelLangauge]);
        } else {
            return $this->render('index', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'modelLangauge' => $modelLangauge]);
        }
    }

    public function actionMultiple($l) {

        $this->layout = "@app/web/themes/nddb/layouts/dashboardLayout.php";
        $model = $this->languageModel($l);
        $modelLangauge = $this->getLanguages();

        $dataProvider = $model['left_column'];
        if (Yii::$app->getRequest()->getQueryParam('flag') == 'export') {
            $this->exportTranslations($model, $dataProvider, $modelLangauge, Yii::$app->getRequest()->getQueryParam('type'), 'multiple');
        }

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()[$model['data']['post']];
            $modelname = Yii::$app->path->define($model['data']['post']);
            $save_model = new $modelname();
            $check = 0;

            $fields = explode(',', $model['multiple']);
            foreach ($fields as $value) {
                if (strlen($post[$value]) == mb_strlen($post[$value], 'UTF-8')) {
                    $model['language_local']->addError($post[$value], 'Data Should be in UTF-8 Format');
                    return $this->render('multiple', ['model' => $model, 'modelLangauge' => $modelLangauge]);
                }
            }
            $list = [];
            $this->_action = $model['action'];
            if ($post['local_code'] == '') {
                $save_model->local_code = Yii::$app->general->getLocalCode($save_model->tableName());
            } else {
                $save_model = $save_model::findOne($post['local_code']);
                if($save_model)
                    $check = 1;
                $history = Yii::$app->path->define($model['data']['post']) . 'History';
                $historyModel = new $history();
                Yii::$app->operation->history($save_model, $historyModel, UPDATE);
                $list[] = $historyModel;
            }
            foreach ($fields as $value) {
                $save_model->{$value} = $post[$value];
            }
            $save_model->attributes = $post;
            if($check==0)
                $save_model->local_code = Yii::$app->general->getLocalCode($save_model->tableName());

            $save_model->{$model['data']['fields']} = $post[$model['data']['fields']];
            $save_model->language_code = $post['language_code'];
            $save_model->is_active = 1;
            array_unshift($list, $save_model);
            $transaction = $this->generalModel->saveTransaction($list, ['language translation', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render('multiple', ['model' => $model, 'modelLangauge' => $modelLangauge]);
    }

    public function actionAlreadyData() {

        $modelname = Yii::$app->path->define($_POST['model']);
        $save_model = new $modelname();
        $data = $this->data($save_model, $_POST['code'], $_POST['language_code'], $_POST['data_fields']);
        if (isset($data)) {
            return Json::encode($data);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    private function data($model, $code, $language, $select) {
        $field = explode('-', $code);
        $selectFields = explode(',', $select);
        array_push($selectFields, 'local_code');
        return $model->find()->select($selectFields)->where(['language_code' => $language, $field[0] => $field[1]])->one();
    }

    private function languageModel($l) {
        if ($l) {
            $data = $this->getLabels($l);
            if ($data) {
                $m1 = Yii::$app->path->define($data['model']);
                $m2 = Yii::$app->path->define($data['post']);
                $relationalField = '';
                if (isset($data['relational_field'])) {
                    $relationArray = explode(',', $data['relational_field']);
                    $relationalField = $relationArray[0];
                }
                if (isset($data['searchModel'])) {
                    $search = Yii::$app->path->define($data['searchModel']);
                    $model['searchModel'] = (new $search());
                }

                $model['left_column'] = $this->leftColumn(new $m1(), $relationalField);
                $model['language_local'] = new $m2();
                $model['data'] = $data;
                $model['multiple'] = isset($data['insert_fields']) ? $data['insert_fields'] : '';
                $model['dd'] = isset($data['dd']) ? $data['dd'] : '';
                $model['action'] = isset($data['action']) ? $data['action'] : '';

                return $model;
            }
            return false;
        }
    }

    private function leftColumn($model, $relationalField = '') {

        $modelName = \yii\helpers\StringHelper::basename(get_class($model));
        if (Yii::$app->session->get('States')) {
            if ($modelName == 'TblStates')
                return $model::find()->where(['is_active' => 1, 'is_delete' => 0/* ,'state_code'=>Yii::$app->session->get('States') */])->andWhere(['like', 'state_code', Yii::$app->session->get('States')])->all();
            else if ($modelName == 'TblDistricts' && !Yii::$app->session->get('Districts'))
                return $model::find()->where(['is_active' => 1, 'is_delete' => 0/* ,'state_code'=>Yii::$app->session->get('States') */])->andWhere(['like', 'state_code', Yii::$app->session->get('States')])->all();
            else if ($modelName == 'TblDistricts' && Yii::$app->session->get('Districts')) {
                $array = explode(',', Yii::$app->session->get('Districts'));
                return $model::find()->where(['is_active' => 1, 'is_delete' => 0, /* 'state_code'=>Yii::$app->session->get('States'), */ 'district_code' => $array])->andWhere(['like', 'state_code', Yii::$app->session->get('States')])->all();
            } else {
                return $model::find()->where(['is_active' => 1, 'is_delete' => 0])->all();
            }
        } else {
            $query = $model::find()->where(['is_active' => 1, 'is_delete' => 0]);
            if (!empty($relationalField))
                $query->orderby($relationalField);
            return $query->all();
        }
    }

    private function getLanguages() {

        $query=\app\models\TblLanguages::find()->select(['code', 'language_name', 'locale'])->where(['is_active' => 1])->andWhere(['<>', 'language_name', 'English']);
        if(Yii::$app->session->get('organizations_type')!='NATIONAL'){
            $language=  \app\modules\geo\models\TblStates::find()->select(['language_code'])->where(['state_code'=>Yii::$app->session->get('States')])->one();
            $query->andWhere(['code'=>$language['language_code']]);
        }
        return $query->all();

    }

    private function getLabels($l) {
        $label = [
            'state' => ['title' => 'State Translation', 'grid' => 'States', 'fields' => 'state_code,state_name', 'post' => 'TblStatesLocal', 'model' => 'TblStates', 'action' => '/geo/tbl-states/index'],
            'district' => ['title' => 'District Translation', 'grid' => 'Districts', 'fields' => 'district_code,district_name', 'post' => 'TblDistrictsLocal', 'model' => 'TblDistricts', 'searchModel' => 'TblDistrictsSearch', 'view' => 'tbl-districts', 'action' => '/geo/tbl-districts/index'],
            'sub-district' => ['title' => 'Sub District Translation', 'grid' => 'Sub Districts', 'fields' => 'sub_district_code,sub_district_name', 'post' => 'TblSubDistrictsLocal', 'model' => 'TblSubDistricts', 'searchModel' => 'TblSubDistrictsSearch', 'view' => 'tbl-sub-districts', 'action' => '/geo/tbl-sub-districts/index'],
            'village' => ['title' => 'Village Translation', 'grid' => 'Village', 'fields' => 'village_code,village_name', 'post' => 'TblVillagesLocal', 'model' => 'TblVillages', 'searchModel' => 'TblVillagesSearch', 'view' => 'tbl-villages', 'action' => '/geo/tbl-villages/index'],
            'hamlet' => ['title' => 'Hamlet Translation', 'grid' => 'Hamlet', 'fields' => 'hamlet_code,hamlet_name', 'post' => 'TblHamletsLocal', 'model' => 'TblHamlets', 'searchModel' => 'TblHamletsSearch', 'view' => 'tbl-hamlets', 'action' => '/geo/tbl-hamlets/index'],
            'miscellaneous' => ['title' => 'Miscellaneous Translation', 'grid' => 'Miscellaneous', 'fields' => 'miscellaneous_code,miscellaneous_name', 'post' => 'TblMiscellaneousLocal', 'model' => 'TblMiscellaneous', 'searchModel' => 'TblMiscellaneousSearch', 'view' => 'tbl-miscellaneous', 'action' => '/globalmaster/tbl-miscellaneous/index'],
            'dcs-type' => ['title' => 'Dcs Type Translation', 'grid' => 'Dcs Type', 'fields' => 'dcs_type_code,dcs_type_name', 'post' => 'TblDcsTypesLocal', 'model' => 'TblDcsTypes', 'action' => '/globalmaster/tbl-dcs-types/index'],
            'caste-category' => ['title' => 'Caste Category Translation', 'grid' => 'Caste', 'fields' => 'caste_category_code,caste_category_name', 'post' => 'TblCasteCategoryLocal', 'model' => 'TblCasteCategory', 'action' => '/globalmaster/tbl-caste-category/index'],
            'unit' => ['title' => 'Unit Translation', 'grid' => 'Unit', 'fields' => 'unit_code,unit_name', 'post' => 'TblUnitsLocal', 'dd' => 'unit', 'insert_fields' => 'local_name,local_short_name', 'model' => 'TblUnits', 'action' => '/globalmaster/tbl-units/index'],
            'land-unit' => ['title' => 'Land Unit Translation', 'grid' => 'Land Unit', 'fields' => 'land_unit_code,land_unit_name', 'post' => 'TblLandUnitLocal', 'model' => 'TblLandUnit', 'action' => '/globalmaster/tbl-land-unit/index'],
            'designation' => ['title' => 'Designation Translation', 'grid' => 'Designation', 'fields' => 'designation_code,designation_name', 'post' => 'TblDesignationLocal', 'model' => 'TblDesignation', 'action' => '/globalmaster/tbl-designation/index'],
            'meeting-type' => ['title' => 'Meeting Type Translation', 'grid' => 'Meeting Type', 'fields' => 'meeting_type_code,meeting_type_name', 'post' => 'TblMeetingTypeLocal', 'model' => 'TblMeetingType', 'action' => '/globalmaster/tbl-meeting-type/index'],
            'animal-type' => ['title' => 'Animal Type Translation', 'grid' => 'Animal Type', 'fields' => 'animal_type_code,animal_type_name', 'post' => 'TblAnimalTypeLocal', 'model' => 'TblAnimalType', 'action' => '/globalmaster/tbl-animal-type/index'],
            'mcc-plant' => ['title' => 'MCC Plant Translation', 'grid' => 'Mcc Plant', 'fields' => 'mcc_plant_code,name', 'post' => 'TblMccPlantLocal', 'model' => 'TblMccPlant', 'action' => '/organisation/tbl-mcc-plant/index'],
            'milk-quality-type' => ['title' => 'Milk Quality Type Translation', 'grid' => 'Milk Quality Type', 'fields' => 'milk_quality_type_code,milk_quality_type_name', 'post' => 'TblMilkQualityTypeLocal', 'model' => 'TblMilkQualityType', 'action' => '/globalmaster/tbl-milk-quality-type/index'],
            'salary-heads' => ['title' => 'Salary Heads Translation', 'grid' => 'Salary Heads', 'fields' => 'salary_head_code,salary_head_name', 'post' => 'TblSalaryHeadsLocal', 'model' => 'TblSalaryHeads', 'action' => '/globalmaster/tbl-salary-heads/index'],
            'ledger-type' => ['title' => 'Ledger Type Translation', 'grid' => 'Ledger Type', 'fields' => 'ledger_type_code,ledger_type_name', 'post' => 'TblLedgerTypeLocal', 'model' => 'TblLedgerType', 'action' => '/globalmaster/tbl-ledger-type/index'],
            'voucher-type' => ['title' => 'Voucher Type Translation', 'grid' => 'Voucher Type', 'fields' => 'voucher_type_code,voucher_type_name', 'post' => 'TblVoucherTypeLocal', 'model' => 'TblVoucherType', 'action' => '/globalmaster/tbl-voucher-type/index'],
            'federation' => ['title' => 'Federation Translation', 'grid' => 'Federation', 'fields' => 'federation_code,federation_name', 'post' => 'TblFederationsLocal', 'model' => 'TblFederations', 'dd' => 'federation', 'insert_fields' => 'local_name,local_address', 'relation' => '', 'relational_field' => '', 'action' => '/organisation/tbl-federations/index'],
            'union' => ['title' => 'Union Translation', 'grid' => 'Union', 'fields' => 'union_code,union_name', 'post' => 'TblUnionsLocal', 'model' => 'TblUnions', 'dd' => 'federation,union', 'insert_fields' => 'local_name,local_address', 'relation' => 'federationCode', 'relational_field' => 'federation_code,federation_name', 'action' => '/organisation/tbl-unions/index'],
            'bank' => ['title' => 'Bank Translation', 'grid' => 'Banks', 'fields' => 'bank_code,bank_name', 'post' => 'TblBanksLocal', 'model' => 'TblBanks', 'relation' => '', 'relational_field' => '', 'action' => '/organisation/tbl-banks/index'],
            'branch' => ['title' => 'Branch Translation', 'grid' => 'Branch', 'fields' => 'branch_code,branch_name', 'post' => 'TblBranchLocal', 'model' => 'TblBranch', 'dd' => 'bank,branch', 'insert_fields' => 'local_name,local_address', 'relation' => 'bankCode', 'relational_field' => 'bank_code,bank_name', 'action' => '/organisation/tbl-branch/index'],
            'route' => ['title' => 'Route Translation', 'grid' => 'Route', 'fields' => 'route_code,route_name', 'post' => 'TblRoutesLocal', 'model' => 'TblRoutes', 'dd' => 'federation,union,route', 'relation' => 'unionCode', 'relational_field' => 'union_code,union_name', 'action' => '/organisation/tbl-routes/index'],
            'dcs' => ['title' => 'Dcs Translation', 'grid' => 'Dcs', 'fields' => 'dcs_code,dcs_name', 'post' => 'TblDcsLocal', 'model' => 'TblDcs', 'dd' => 'federation,union,dcs', 'insert_fields' => 'local_name,local_address,local_name_short', 'relation' => 'unionCode', 'relational_field' => 'union_code,union_name', 'action' => '/organisation/tbl-dcs/index'],
            'sub-center' => ['title' => 'Sub Center Translation', 'grid' => 'Sub Center', 'fields' => 'sub_center_code,sub_center_name', 'post' => 'TblSubCenterLocal', 'model' => 'TblSubCenter', 'dd' => 'federation,union,dcs,sub_center', 'insert_fields' => 'local_name,local_address', 'relation' => 'dcsCode', 'relational_field' => 'dcs_code,dcs_name', 'action' => '/organisation/tbl-sub-center/index'],
            'ledger-group' => ['title' => 'Ledger Group Translation', 'grid' => 'Ledger Group', 'fields' => 'ledger_group_code,ledger_group_name', 'post' => 'TblLedgerGroupLocal', 'model' => 'TblLedgerGroup', 'action' => '/dcsaccounting/tbl-ledger-group/index'],
            'ledger' => ['title' => 'Ledger Translation', 'grid' => 'Ledger', 'fields' => 'ledger_code,ledger_name', 'post' => 'TblLedgerLocal', 'model' => 'TblLedger', 'action' => '/dcsaccounting/tbl-ledger/index'],
            'asset-group' => ['title' => 'Asset Group Translation', 'grid' => 'Asset Group', 'fields' => 'asset_group_code,asset_group_name', 'post' => 'TblAssetGroupLocal', 'model' => 'TblAssetGroup', 'action' => '/dcsaccounting/tbl-asset-group/index'],
            'asset-sub-group' => ['title' => 'Asset Sub Group Translation', 'grid' => 'Asset Sub Group', 'fields' => 'asset_sub_group_code,asset_sub_group_name', 'post' => 'TblAssetSubGroupLocal', 'model' => 'TblAssetSubGroup', 'action' => '/dcsaccounting/tbl-asset-sub-group/index'],
            'union-bill-head' => ['title' => 'Union Bill Head Translation', 'grid' => 'Union Bill Head', 'fields' => 'union_bill_head_code,union_bill_head_name', 'post' => 'TblUnionBillHeadLocal', 'model' => 'TblUnionBillHead', 'action' => '/dcsaccounting/tbl-union-bill-head/index'],
            'tax-group' => ['title' => 'Tax Group Translation', 'grid' => 'Tax Group', 'fields' => 'tax_group_code,tax_group_name', 'post' => 'TblTaxGroupLocal', 'model' => 'TblTaxGroup', 'action' => '/dcsaccounting/tbl-tax-group/index'],
            'tax' => ['title' => 'Tax Translation', 'grid' => 'Tax', 'fields' => 'tax_code,tax_name', 'post' => 'TblTaxLocal', 'model' => 'TblTax', 'action' => '/dcsaccounting/tbl-tax/index'],
            'basic-tax' => ['title' => 'Basic Tax Translation', 'grid' => 'Basic Tax', 'fields' => 'basic_tax_code,basic_tax_name', 'post' => 'TblBasicTaxLocal', 'model' => 'TblBasicTax', 'action' => '/dcsaccounting/tbl-basic-tax/index'],
            'product-group' => ['title' => 'Product Group Translation', 'grid' => 'Products', 'fields' => 'product_group_id,product_group_name', 'post' => 'TblProductGroupLocal', 'model' => 'TblProductGroup', 'action' => '/inventory/tbl-product-group/index'],
            'product' => ['title' => 'Product Translation', 'grid' => 'Product', 'fields' => 'product_code,product_name', 'post' => 'TblProductLocal', 'model' => 'TblProduct', 'action' => '/inventory/tbl-product/index'],
            'member-classification' => ['title' => 'Member Classification Translation', 'grid' => 'Member Classification', 'fields' => 'member_classification_code,member_classification_name', 'post' => 'TblMemberClassificationLocal', 'model' => 'TblMemberClassification', 'action' => '/dcsoperation/tbl-member-classification/index'],
        ];
        return $label[$l];
    }

    public function exportTranslations($model, $dataProvider, $modelLangauge, $type, $exportType = '',$exportField='') {

        $header = \app\modules\translation\Translation::getContentHeaders($type);
        $exportField = empty($exportField)?'local_name':$exportField;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;
        $column = 'A';

        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $model['data']['grid']);
        $column++;

        if ($exportType == 'multiple') {

            $localFields = explode(',', $model['data']['insert_fields']);
            $relationArray = explode(',', $model['data']['relational_field']);
            foreach ($modelLangauge as $col) {
                foreach ($localFields as $l) {
                    $name = ucwords(str_replace('_', ' ', $l));
                    $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $name . '-' . $col->language_name);
                    $column++;
                }
            }
            $rowCount = 2;
            foreach ($localFields as $l) {
                ${$l} = '';
            }
            $oldValue = '';
            $newValue = '';
            foreach ($dataProvider as $key => $row) {
                $column = 'A';
                $field = explode(',', $model['data']['fields']);
                $local_data = \app\modules\translation\Translation::getLanguages($model['language_local'], $field[0], $row->{$field[0]});

                if (!empty($model['data']['relation'])) {
                    $newValue = $row->{$relationArray[0]};
                    if ($newValue != $oldValue) {
                        if ($key != 0)
                            $rowCount++;
                        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $row->{$model['data']['relation']}->{$relationArray[1]});
                        $rowCount++;
                    }
                }
                $fieldName = str_replace('code', 'name', $field[0]);
//                echo $row->{$model['data']['relation']}->{$relationArray[1]}.'-'.$row->{$fieldName}.'<br>';

                $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $row->{$fieldName});
                $column++;

                foreach ($modelLangauge as $col) {

                    $val = \app\modules\translation\Translation::getTranslation($model['language_local'], $model['data']['insert_fields'], $col->code, $field[0], $row->{$field[0]});
                    if ($val) {
                        foreach ($localFields as $l) {
                            ${$l} = $val->$l;
                        }
                    }
                    foreach ($localFields as $l) {
                        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, ${$l});
                        $column++;
                        ${$l} = '';
                    }
                }
                $rowCount++;
                if (!empty($model['data']['relation'])) {
                    $oldValue = $row->{$relationArray[0]};
                }
            }
//            exit;
        } else {

            foreach ($modelLangauge as $col) {
                $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $col->language_name);
                $column++;
            }

            $rowCount = 2;
            foreach ($dataProvider as $row) {
                $column = 'A';
                $field = explode(',', $model['data']['fields']);
                $local_data = \app\modules\translation\Translation::getLanguages($model['language_local'], $field[0], $row->{$field[0]},$exportField);

                $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $row->{$field[1]});
                $column++;

                foreach ($modelLangauge as $col) {
                    $local_name = '';
                    $val = \app\modules\translation\Translation::ifExist($col->code, $local_data);

                    if ($val) {
                        //$local_name = $val->local_name;
                        $local_name = $val->{$exportField};
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $local_name);
                    $column++;
                    $local_name = '';
                }
                $rowCount++;
                $local_name = '';
            }
        }
        $fileName = "results." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    protected function customRedirect() {
        return $this->redirect([$this->_action]);
    }
}
