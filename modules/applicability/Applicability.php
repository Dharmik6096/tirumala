<?php

namespace app\modules\applicability;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblRoutes;
use app\modules\organisation\models\TblDcs;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\models\GeneralModel;
use yii\helpers\Url;
use ReflectionClass;
use DateTime;
use yii\helpers\FileHelper;
use app\models\TblUserOrganizationMapping;
use webvimark\modules\UserManagement\models\User;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;

/**
 * applicability module definition class
 */
class Applicability extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\applicability\controllers';
    public $model, $field_name, $field_value, $union_code, $oldModel, $historyModel, $trans_label, $searchModel;
    public $is_union = true;
    public $dcs_filters = ['society' => 'Society', 'routes' => 'Routes'];
    public $fields = [];
    public $top_section = true;
    public $payment = false;
    public $title = '';
    public $actions = false;
    public $select_from_all = false;
    public $ratechart = false;
    public $shift_type = '';
    //put your code here
    protected $generalModel;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
        $this->union_code = !empty(Yii::$app->session->get('Unions') && count(explode(',', Yii::$app->session->get('Unions'))) == 1) ? Yii::$app->session->get('Unions') : '';

//        $this->searchModel= new $this->model->className().'Search()';
        // custom initialization code goes here
    }

    protected function customRedirect() {
        return Yii::$app->controller->redirect(Url::previous());
    }

    protected function customRender() {
        $field_name = $this->field_name;
        $selected = $this->getDcs($this->top_section);
        $dcs_list = $this->loadUnionDcs($selected, '');
        $dcs_list = ArrayHelper::map($dcs_list, 'dcs_code', 'dcs_name');
        $searchModel = $this->searchModel;
        $searchModel->$field_name = $this->field_value;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return Yii::$app->controller->render('/../../applicability/views/default/create', [
                    'model' => $this->model,
                    'field_name' => $field_name,
                    'dcs_list' => $dcs_list,
                    'selected' => $selected,
                    'filters' => $this->dcs_filters,
                    'filter_data' => $this->getFilterData(),
                    'union_code' => $this->union_code,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'field_code' => $this->field_value,
                    'model_name' => $this->model->className(),
                    'top_section' => $this->top_section,
                    'fields' => $this->fields,
                    'is_union' => $this->is_union,
                    'payment' => $this->payment,
                    'title' => $this->title,
                    'actions' => $this->actions,
                    'select_from_all' => $this->select_from_all,
                    'ratechart' => $this->ratechart,
                    'shift_type' => $this->shift_type,
        ]);
    }

    public function createApp() {

        $model = $this->model;
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();

        $field_name = $this->field_name;
        if (Yii::$app->request->post()) {
            //$model->union_code = $this->union_code;
            $model->$field_name = $this->field_value;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $dataold = $this->model->find()->where([$this->field_name => $this->field_value])->all();
                    $returnedArray = \yii\helpers\ArrayHelper::getColumn($dataold, 'dcs_code');
                    $toRevoke = array_intersect($returnedArray, $model->dcs_code);
                    $toAssign = $model->dcs_code;
                    //$toAssign = array_diff($returnedArray, $model->dcs_code);
                    //$toRevoke = array_diff($model->dcs_code, $returnedArray);
                    $mappingList = [];
                    //var_dump($returnedArray);
                    //var_dump($model->dcs_code);
                    foreach ($toRevoke as $value) {
                        if (!empty($value)) {
                            //echo $value.'<br/>';
                            $r = new ReflectionClass($this->model->className());
                            $appModel = $r->newInstanceArgs();
                            $appModel = $appModel->find()->where(['dcs_code' => $value, $field_name => $this->field_value])->one();
                            $h = new ReflectionClass($this->historyModel->className());
                            $appHistory = $h->newInstanceArgs();
                            Yii::$app->operation->history($appModel, $appHistory, DELETE);
                            array_push($mappingList, $appHistory);
                            array_push($mappingList, $appModel);
                        }
                    }

                    foreach ($toAssign as $value) {
                        $r = new ReflectionClass($this->model->className());
                        $appModel = $r->newInstanceArgs();
                        $data = $model->attributes;
                        $appModel->setAttributes($data);
                        $primaryKey = $model->tableSchema->primaryKey[0];
                        unset($appModel->$primaryKey);
                        $appModel->dcs_code = $value;
                        $appModel->$field_name = $this->field_value;
                        //$appModel->union_code = $this->union_code;  

                        $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);

                        $check = $this->checkDuplicate($appModel);
                        if ($check == 1) {
                            $model->addError('wef_date', $appModel->wef_date . ' date already taken by dcs.');
                            return $this->customRender();
                        }

                        $appModel->union_code = $this->union_code;
                        $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);
                        $check = $this->checkDuplicate($appModel);
                        if ($check == 1) {
                            $model->addError('wef_date', $appModel->wef_date . ' date already taken by dcs.');
                            return $this->customRender();
                        }

                        array_push($mappingList, $appModel);
                    }
                    $transaction = $this->generalModel->appTransaction($mappingList, [$this->trans_label, 'create']);
                    if ($transaction !== FALSE) {
                        $mname = \yii\helpers\StringHelper::basename(get_class($this->model));
                        if ($transaction == 'customRedirect' && $mname == 'TblPurchaseRateApplicability') {
                            $files = [];
                            foreach ($mappingList as $mapping) {
                                $cmname = \yii\helpers\StringHelper::basename(get_class($mapping));
                                if ($cmname == 'TblPurchaseRateApplicability' && Yii::$app->general->isVendor($mapping->dcs_code, 'BIPL')) {
                                    $rfiles = $mapping->generateBiplRateFiles();
                                    if ($rfiles != false) {
                                        if (!empty($files))
                                            $files = array_merge($files, $rfiles);
                                        else
                                            $files = $rfiles;
                                    }
                                }
                            }
                            $files = array_values($files);
                            $app = new TblPurchaseRateApplicability();
                            foreach ($files as $key => $value) {
                                $cp_code = key($value);
                                $cp_path = Yii::$app->params['biplDirPath'] . 'EKOMILK/' . $cp_code . '/' . 'MASFILES';
                                if (Yii::$app->general->checkDirectory($cp_path))
                                    $app->generateEncFile($value[$cp_code], $cp_path);
                            }
                        }
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function getFilterData() {
        $filters = $this->dcs_filters;
        $filter_data = [];
        switch (1) {
            case key_exists('routes', $filters):
                $routs = new TblRoutes();
                $filter_data['routes'] = $routs->getRoutes($this->union_code);
                break;
            case in_array('plants', $filters):
                break;
            case in_array('mcc', $filters):
                break;
            default :
        }
        return $filter_data;
    }

    public function getDcs($top_section) {
        $field_name = $this->field_name;
        if ($this->select_from_all == false)
            $query = $this->model->find()->select('dcs_code')->where([$field_name => $this->field_value]);
        else
            $query = $this->model->find()->select('dcs_code');
//        if ($top_section)
//            $query->andWhere(['<=', 'wef_date', date('Y-m-d')]);
        $values = $query->all();
        $selected = ArrayHelper::getColumn($values, 'dcs_code');
        return $selected;
    }

    public function getDcsAlert($union_code, $id) {
        $query = $this->model->getPaymentCycleDcs($this->field_value);
        $query2 = $this->model->getPaymentCycleDcsWithGap($this->field_value);
        $message = [];
        $message1 = [];
        $dcs = [];
        $messagestring = '';

        for ($i = 0; $i < count($query); $i++) {
            if (is_array($id) && in_array($query[$i]->dcs_code, $id)) {
                $date = Yii::$app->controls->view_date($query[$i]->paymentCycle->from_date) . ' to ' . Yii::$app->controls->view_date($query[$i]->paymentCycle->to_date);
                $message[] = $query[$i]->dcsCode->dcs_name . ' [' . $date . ']';
                $dcs[] = $query[$i]->dcs_code;
            }
        }
        if (count($message) > 0) {
            $messagestring = 'Following societies already has payment cycle conflicting with current cycle <br/>' . implode('<br/>', $message);
        }

        for ($i = 0; $i < count($query2); $i++) {
            if (is_array($id) && in_array($query2[$i]->dcs_code, $id)) {
                $date = Yii::$app->controls->view_date($query2[$i]->paymentCycle->from_date) . ' to ' . Yii::$app->controls->view_date($query2[$i]->paymentCycle->to_date);
                $message1[] = $query2[$i]->dcsCode->dcs_name . ' [' . $date . ']';
                $dcs[] = $query2[$i]->dcs_code;
            }
        }
        if (count($message1) > 0) {
            $messagestring = $messagestring . '<br/> Following societies has payment cycle gap with current cycle <br/>' . implode('<br/>', $message1);
        }
        return [0 => $messagestring, 1 => $dcs];
    }

    public function loadUnionDcs($dcs = [], $union_code) {
        //var_dump($dcs); exit;
        $dcsList = TblDcs::find()->joinWith(['societyCodes'])->where(['tbl_dcs.union_code' => $union_code, 'is_active' => 1])->andWhere(['not in', 'tbl_dcs.dcs_code', $dcs])->andWhere(['not', ['tbl_society_codes.bmc_code' => 0]])->andWhere(['not', ['tbl_society_codes.bmc_code' => null]]);
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcsList->andWhere(['tbl_dcs.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        $dcsList = $dcsList->all();
        return $dcsList;
    }

    public function checkDuplicate($model) {
        $field_name = $this->field_name;
        $query = $this->model->find()->where(['dcs_code' => $model->dcs_code, $field_name => $this->field_value]);
        foreach ($this->fields as $key => $f) {
            if (in_array('create', $f['view'])) {
                $query->where([$key => $model->{$key}]);
            }
        }
        return $query->all();
    }

    public function paymentApplicability() {
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();

        if (Yii::$app->request->post()) {

            if ($this->model->load(Yii::$app->request->post()) && $this->model->dcs_code != '') {

                if ($this->model->validate() && $this->model->dcs_code != '') {
                    $save_model = [];
                    foreach ($this->model->dcs_code as $dcs) {
                        $paymentCycleModel = new TblDcsPaymentCycle();
                        $paymentCycleData = $paymentCycleModel->findOne($this->field_value);
                        $new_model = new ReflectionClass($this->model->className());
                        $model = $new_model->newInstanceArgs();
                        $model->from_date = $paymentCycleData->from_date;
                        $model->to_date = $paymentCycleData->to_date;
                        $model->dcs_payment_cycle_code = $this->field_value;
                        $model->is_lock = 0;
                        $model->data_lock = 0;
                        /* for (; $from_date < $to_date;) {
                          $new_model = new ReflectionClass($this->model->className());
                          $model = $new_model->newInstanceArgs();
                          $model->dcs_payment_cycle_code = $this->field_value;

                          $tmp_Date = clone $from_date;
                          $end_date = clone $from_date;
                          $tmp_Date->modify("last day of this month");
                          $end_date->modify("+" . $paymentCycleData->interval_value . " day");
                          $model->from_date = $from_date->format('Y-m-d');
                          if ($end_date > $tmp_Date) {
                          if ($tmp_Date < $to_date)
                          $end_date = clone $tmp_Date;
                          else {
                          $end_date = clone $to_date;
                          }
                          }
                          $model->to_date = $end_date->format('Y-m-d'); */
                        $model->dcs_code = $dcs;
                        $save_model[] = $model;
                        /* $from_date = clone $end_date;
                          $from_date->modify("+ 1 day"); */
                        //}
                    }
                    $transaction = $this->generalModel->appTransaction($save_model, [$this->trans_label, 'create']);
                    if ($transaction !== FALSE) {
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function vendorApplicability() {
        $model = $this->model;
        $searchName = $this->model->className() . 'Search';
        $historyName = $this->model->className() . 'History';
        $this->searchModel = new $searchName();
        $this->historyModel = new $historyName();

        $field_name = $this->field_name;
        if (Yii::$app->request->post()) {
            if ($this->is_union) {
                $model->union_code = $this->union_code;
            }
            $model->$field_name = $this->field_value;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $dataold = $this->model->find()->where([$this->field_name => $this->field_value])->all();
                    $returnedArray = \yii\helpers\ArrayHelper::getColumn($dataold, 'dcs_code');
                    $toRevoke = array_intersect($returnedArray, $model->dcs_code);
                    $toAssign = $model->dcs_code;
                    //$toAssign = array_diff($returnedArray, $model->dcs_code);
                    //$toRevoke = array_diff($model->dcs_code, $returnedArray);
                    $mappingList = [];
                    //var_dump($returnedArray);
                    //var_dump($model->dcs_code);
                    //var_dump($toRevoke);exit;
                    foreach ($toRevoke as $value) {
                        if (!empty($value)) {
                            //echo $value.'<br/>';
                            $r = new ReflectionClass($this->model->className());
                            $appModel = $r->newInstanceArgs();
                            $appModel = $appModel->find()->where(['dcs_code' => $value, $field_name => $this->field_value])->one();
                            $h = new ReflectionClass($this->historyModel->className());
                            $appHistory = $h->newInstanceArgs();
                            Yii::$app->operation->history($appModel, $appHistory, DELETE);
                            array_push($mappingList, $appHistory);
                            array_push($mappingList, $appModel);
                        }
                    }

                    foreach ($toAssign as $value) {
                        $r = new ReflectionClass($this->model->className());
                        $appModel = $r->newInstanceArgs();
                        $data = $model->attributes;
                        //$appModel->setAttributes($data);
                        // $appModel->society_vendor_code='';
                        $appModel->dcs_code = $value;
                        $appModel->$field_name = $this->field_value;

                        if ($this->is_union) {
                            $appModel->union_code = $this->union_code;
                        }
                        if (key_exists('wef_date', $this->fields)) {
                            $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);

                            $check = $this->checkDuplicate($appModel);
                            if ($check == 1) {
                                $model->addError('wef_date', $appModel->wef_date . ' date already taken by dcs.');
                                return $this->customRender();
                            }

                            $appModel->union_code = $this->union_code;
                            $appModel->wef_date = Yii::$app->formatter->asDate($model->wef_date, DATE_FORMAT);
                            $check = $this->checkDuplicate($appModel);
                            if ($check == 1) {
                                $model->addError('wef_date', $appModel->wef_date . ' date already taken by dcs.');
                                return $this->customRender();
                            }
                        }
                        array_push($mappingList, $appModel);
                    }
                    $transaction = $this->generalModel->appTransaction($mappingList, [$this->trans_label, 'create']);
                    if ($transaction == 'customRedirect') {
                        $orgMap = [];
                        foreach ($mappingList as $map) {
                            $userModel = new User();
                            $users = $userModel->findByRole([$map->vendor_code]);
                            foreach ($users as $user) {
                                //TblUserOrganizationMapping::deleteAll(['user_id' => $user->id]);
                                if (empty($user->user_type_id)) {
                                    $user->user_type_id = 4;
                                    array_push($orgMap, $user);
                                }
                                $modelNew = new TblUserOrganizationMapping();
                                $modelNew->organization_code = $map->dcs_code;
                                $modelNew->organization_type = 'DCS';
                                $modelNew->user_id = $user->id;
                                $modelNew->is_active = $user->is_active;
                                Yii::$app->operation->defaults($modelNew, INSERT);
                                array_push($orgMap, $modelNew);
                                //$modelNew->save();
                            }
                            if (strtolower($map->vendor_code) == 'eipl') {
                                $path = Yii::$app->params['eiplDirPath'] . $map->dcs_code . '/';
                                if (!file_exists($path) || !is_dir($path)) {
                                    FileHelper::createDirectory($path);
                                }
                            }
                        }
                        $this->generalModel->saveTransaction($orgMap, ['society mapping', 'create']);
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    public function getDcsAlertRateChart($union_code, $id, $wef_date, $shift) {
        if ($shift == 'all') {
            $shiftarray = ['all', 'morning', 'evening'];
        } else {
            $shiftarray = ['all', $shift];
        }
        $model_name = str_replace('\\', '_', $this->model->className());
        $cname = explode('_', $model_name);
        $cname = end($cname);
        $nameforid = strtolower($cname);
        $query = $this->model->find()
                ->select(['dcs_code', 'tbl_purchase_rate.shift_applicability', 'tbl_purchase_rate.purchase_rate_code'])
                ->joinWith(['purchaseRateCode.shiftApplicability'])
                ->where(['dcs_code' => $id, 'convert(date, tbl_purchase_rate.wef_date, 103)' => $wef_date, 'tbl_purchase_rate.originating_org_type' => 'UNION', 'tbl_purchase_rate.union_code' => $union_code])
                ->andWhere(['tbl_shift.shift' => $shiftarray])->all();
        $message = [];
        $dcs = [];
        $messagestring = '';

        for ($i = 0; $i < count($query); $i++) {
            $message[] = $query[$i]->dcsCode->dcs_name . ' -' . $query[$i]->purchase_rate_code;
            $dcs[] = $query[$i]->dcs_code;
        }
        if (count($message) > 0) {
            $messagestring = 'Following are the current <b>society - purchase rate</b> applicabilities.<br/>' . implode('<br/>', $message);
        }
        return [0 => $messagestring, 1 => $dcs];
    }

}
