<?php

namespace app\modules\report\controllers;

use yii\web\Controller;
use Yii;
use app\modules\report\models\TblMilkCollection;
use app\modules\report\models\TblMilkCollectionSearch;
use yii\data\ActiveDataProvider;
//use yii\helpers\ArrayHelper;
use \yii\data\ArrayDataProvider;
use app\modules\report\models\TblDpuRequestSearch;

/**
 * Default controller for the `modules` module
 */
class DefaultController extends \app\controllers\ChildController {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';

        $start_date = !empty($model->min_date) ? date('Y-m-d', strtotime($model->min_date)) : '';
        $end_date = !empty($model->max_date) ? date('Y-m-d', strtotime($model->max_date)) : '';

        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }
        if (empty($model->dcs_code)) {
            $model->dcs_code = NULL;
        }

        $result = \Yii::$app->db->createCommand("{CALL sp_dcs_collection_data(:union_code,:dcs_code,:start_date,:end_date)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':dcs_code', $model->dcs_code)
                ->bindValue(':start_date', $start_date)
                ->bindValue(':end_date', $end_date);
        $query = $result->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_code' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'dcs_code',
                    'dcs_name',
                    'total_shift',
                    'received_shift_data',
                    'total_qty',
                    'avg_fat',
                    'avg_snf',
                    'amount',
                    'dcs_code_ex'
                ],
            ],
        ]);

        return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionView() {
        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);


        $columns = [];

        $dcs_code = !empty($request['TblMilkCollectionSearch']['dcs_code']) ? $request['TblMilkCollectionSearch']['dcs_code'] : '';
        $start_date = !empty($model->min_date) ? date('Y-m-d', strtotime($model->min_date)) : '';
        $end_date = !empty($model->max_date) ? date('Y-m-d', strtotime($model->max_date)) : '';


        /* for ($i = $start_date; $i <= $end_date; $i++) {
          $date = $i;

          $columns = ['label' => "Total Shift", 'value' => ""];
          } */

        $query = (new \yii\db\Query())
                ->select(["tbl_villages.village_name", "COUNT(tbl_milk_collection.shift) AS get_shift_data", "(DATEDIFF(day,'" . $start_date . "','" . $end_date . "')+1)*2 AS total_shift"])
                ->from('tbl_villages')
                ->join('INNER JOIN', 'tbl_milk_collection', 'tbl_milk_collection.village_code=tbl_villages.village_code')
                ->where(['tbl_milk_collection.dcs_code' => $dcs_code])
                ->andFilterWhere(['between', 'CAST(tbl_milk_collection.date_time_of_collection AS DATE)', $start_date, $end_date])
                ->GroupBy('tbl_villages.village_name,tbl_milk_collection.date_time_of_collection');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);



        //$query->andFilterWhere(['like', 'village_name', $this->village_name]);
        //$dataProvider =  $model;

        return $this->render('view', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionList() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';

        $start_date = !empty($model->min_date) ? date('Y-m-d', strtotime($model->min_date)) : '';
        $end_date = !empty($model->max_date) ? date('Y-m-d', strtotime($model->max_date)) : '';

        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }
        if (empty($model->plant_code)) {
            $model->plant_code = NULL;
        }
        if (empty($model->mcc_code)) {
            $model->mcc_code = NULL;
        }
        if (empty($model->bmc_code)) {
            $model->bmc_code = NULL;
        }
        if (empty($model->dcs_code)) {
            $model->dcs_code = NULL;
        }

        $result = \Yii::$app->db->createCommand("{CALL sp_union_data(:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code,:start_date,:end_date)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':plant_code', $model->plant_code)
                ->bindValue(':mcc_code', $model->mcc_code)
                ->bindValue(':bmc_code', $model->bmc_code)
                ->bindValue(':dcs_code', $model->dcs_code)
                ->bindValue(':start_date', $start_date)
                ->bindValue(':end_date', $end_date);
        $query = $result->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_name',
                    'district_name',
                    'mcc_name',
                    'society_code',
                    'dcs_code_ex',
                    'society_name',
                    'first_date_of_data_received',
                    'last_date_of_data_received',
                    'total_shift',
                    'no_of_shift',
                    'shift_per'
                ],
            ],
        ]);

        return $this->render('list', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionUnionCount() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($model->min_date) ? date('Y-m-d', strtotime($model->min_date)) : NULL;
        $end_date = !empty($model->max_date) ? date('Y-m-d', strtotime($model->max_date)) : NULL;


        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }

        $result = \Yii::$app->db->createCommand("{CALL sp_union_collection_data(:union_code,:start_date,:end_date)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':start_date', $start_date)
                ->bindValue(':end_date', $end_date);
        $query = $result->queryAll();

        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'total_society',
                    'data_received_for_society',
                ],
            ],
        ]);

        return $this->render('count', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionNoCollectionShifts() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }
        if (empty($model->shift)) {
            $model->shift = NULL;
        }
        $end_date = !empty($model->max_date) ? date('Y-m-d H:i:s', strtotime($model->max_date . ' ' . $model->shift)) : NULL;
        $count = empty($request['shift_no']) ? 5 : $request['shift_no'];

        $result = \Yii::$app->db->createCommand("{CALL sp_no_collection_shifts(:union_code,:date,:count)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':count', $count)
                ->bindValue(':date', $end_date);
        $query = $result->queryAll();

        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_name',
                    'dcs_name',
                    'dcs_code',
                    'dcs_code_ex',
                ],
            ],
        ]);

        return $this->render('no_cln', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionNoCollectionSociety() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }
        if (empty($model->shift)) {
            $model->shift = NULL;
        }
        $end_date = !empty($model->max_date) ? date('Y-m-d H:i:s', strtotime($model->max_date . ' ' . $model->shift)) : NULL;
        $count = empty($request['shift_no']) ? 5 : $request['shift_no'];

        $result = \Yii::$app->db->createCommand("{CALL sp_no_collection_dcs(:union_code,:date,:count)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':count', $count)
                ->bindValue(':date', $end_date);
        $query = $result->queryAll();

        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'total_dcs',
                ],
            ],
        ]);

        return $this->render('no_cln_dcs', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionDpuRequest() {

        $request = Yii::$app->request->queryParams;

        $model = new TblDpuRequestSearch();
        $model->load($request);
        $model->scenario = 'union_count';

        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : '';
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : '';

        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }

        $result = \Yii::$app->db->createCommand("{CALL sp_dpu_request(:union_code,:start_date,:end_date)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':start_date', $start_date)
                ->bindValue(':end_date', $end_date);
        $query = $result->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_name',
                    'dcs_code',
                    'dcs_code_ex',
                    'dcs_name',
                //'society_name',
                ],
            ],
        ]);

        return $this->render('list_dpu_dcs', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionDpuRequestSummary() {

        $request = Yii::$app->request->queryParams;

        $model = new TblDpuRequestSearch();
        $model->load($request);
        $model->scenario = 'union_count';

        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = NULL;
        }

        $result = \Yii::$app->db->createCommand("{CALL sp_dpu_request_summary(:union_code,:start_date,:end_date)}")
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':start_date', $start_date)
                ->bindValue(':end_date', $end_date);
        $query = $result->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'total_dcs',
                    'dpu_dcs',
                    'col_dcs',
                    'no_col_dcs',
                //'society_name',
                ],
            ],
        ]);

        return $this->render('list_dpu', ['model' => $model, 'dataProvider' => $dataProvider,]);
    }

    public function actionShiftReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        if (isset($model->bmc_code) && $model->bmc_code == 0 && !empty(Yii::$app->session->get('BMC'))) {
            $model->bmc_code = Yii::$app->session->get('BMC');
        }
        if (isset($model->dcs_code) && $model->dcs_code == 0 && !empty(Yii::$app->session->get('Dcs'))) {
            $model->dcs_code = Yii::$app->session->get('Dcs');
        }
        $query = [];
        $extra = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Shiftwise_CrossTab_1(:Date1,:Date2,:Union_code,:p_bmc_code,:p_dcs_code)}")
                    ->bindValue(':Union_code', $model->union_code)
                    ->bindValue(':p_bmc_code', $model->bmc_code)
                    ->bindValue(':p_dcs_code', $model->dcs_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM');
            $query = $result->queryAll();
            $extra = [];
            while (strtotime($start_date) <= strtotime($end_date)) {
                $m = date("d/m/Y", strtotime($start_date)) . 'M';
                $e = date("d/m/Y", strtotime($start_date)) . 'E';
                array_push($extra, ['header' => $m, 'value' => function($model) use ($m) {
                        return $model[$m];
                    }, 'filter' => false]);
                array_push($extra, ['header' => $e, 'value' => function($model) use ($e) {
                        return $model[$e];
                    }, 'filter' => false]);
                $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_name',
                    'district_name',
                    'dcs_code',
                    'dcs_name'
                ],
            ],
        ]);

        return $this->render('shift_report', ['model' => $model, 'dataProvider' => $dataProvider, 'extra' => $extra]);
    }

    public function actionDailyReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        $extra = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Shiftwise_CrossTab_2(:Date1,:Date2,:Union_code)}")
                    ->bindValue(':Union_code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM');
            $query = $result->queryAll();
            //;var_dump($query);exit;
            $extra = [];
            while (strtotime($start_date) <= strtotime($end_date)) {
                array_push($extra, ['header' => date("d-m-Y", strtotime($start_date)), 'value' => function($model) use ($start_date) {
                        return $model[$start_date];
                    }, 'filter' => false]);
                $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_name',
                    'district_name',
                    'dcs_code',
                    'dcs_name'
                ],
            ],
        ]);

        return $this->render('daily_report', ['model' => $model, 'dataProvider' => $dataProvider, 'extra' => $extra]);
    }

    public function actionUnionReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        $extra = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Shiftwise_CrossTab_Union_Wise(:Date1,:Date2,:Union_code)}")
                    ->bindValue(':Union_code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM');
            $query = $result->queryAll();
            //;var_dump($query);exit;
            $extra = [];
            while (strtotime($start_date) <= strtotime($end_date)) {
                array_push($extra, ['header' => date("d-m-Y", strtotime($start_date)), 'value' => function($model) use ($start_date) {
                        return $model[$start_date];
                    }, 'filter' => false]);
                $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_name',
                ],
            ],
        ]);

        return $this->render('union_report', ['model' => $model, 'dataProvider' => $dataProvider, 'extra' => $extra]);
    }

    public function actionDcsShiftReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
//        $model->scenario='union_count';
        $start_date = !empty($model->min_date) ? date('Y-m-d', strtotime($model->min_date)) : NULL;
        $end_date = !empty($model->max_date) ? date('Y-m-d', strtotime($model->max_date)) : NULL;

        if (empty($model->union_code)) {
            //$model->union_code = Yii::$app->session->get('Unions');
            $model->union_code = NULL;
        }
        if (empty($model->dcs_code)) {
            $model->dcs_code = NULL;
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Dcs_Shiftwise_Data(:Date1,:Date2,:Union_Code,:dcs_code,:shift)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':dcs_code', $model->dcs_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':shift', !empty($model->shift) ? $model->shift : 3);
            $query = $result->queryAll();
            //;var_dump($query);exit;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['dcs_name' => SORT_ASC],
                'attributes' => [
                    'dcs_code',
                    'dcs_name',
                    'dtdate',
                    'qty',
                    'avg_fat',
                    'avg_snf',
                    'kg_fat',
                    'kg_snf',
                    'avg_rate',
                    'total_amount',
                ],
            ],
        ]);

        return $this->render('dcs_shift_report', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionUnionShiftReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Union_Shiftwise_Data(:Date1,:Date2,:Union_Code,:shift)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':shift', !empty($model->shift) ? $model->shift : 3);
            $query = $result->queryAll();
            //;var_dump($query);exit;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'dtdate',
                    'qty',
                    'avg_fat',
                    'avg_snf',
                    'kg_fat',
                    'kg_snf',
                    'avg_rate',
                    'total_amount',
                ],
            ],
        ]);

        return $this->render('union_shift_report', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionUnionShiftSummaryReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Union_Shiftwise_Summary(:Date1,:Date2,:Union_Code,:shift)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':shift', !empty($model->shift) ? $model->shift : 3);
            $query = $result->queryAll();
            //var_dump($query);exit;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'dtdate',
                    'qty',
                    'avg_fat',
                    'avg_snf',
                    'kg_fat',
                    'kg_snf',
                    'avg_rate',
                    'total_amount',
                ],
            ],
        ]);

        return $this->render('union_shift_summary', ['model' => $model, 'dataProvider' => $dataProvider, 'summary' => true]);
    }

    public function actionDpuCalibration() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Union_Shiftwise_Summary(:Date1,:Date2,:Union_Code,:shift)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':shift', !empty($model->shift) ? $model->shift : 3);
            $query = $result->queryAll();
            //var_dump($query);exit;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'dtdate',
                    'qty',
                    'avg_fat',
                    'avg_snf',
                    'kg_fat',
                    'kg_snf',
                    'avg_rate',
                    'total_amount',
                ],
            ],
        ]);

        return $this->render('dpu_calibration', ['model' => $model, 'dataProvider' => $dataProvider, 'summary' => true]);
    }

    public function actionDpmcuWorkingStatus() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        //  var_dump($request);die;
        $model->scenario = 'union_count';
        $start_date = !empty($model->min_date) ? date('Y-m-d', strtotime($model->min_date)) : NULL;
        $end_date = $start_date;
        if ($model->vendor == '0') {
            $vendor = 'BIPL,EIPL,REIL';
        } else {
            $vendor = $model->vendor;
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_DPMCU_Working_status(:Date1,:Date2,:Union_Code,:Vendor)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':Vendor', ',' . $vendor . ',');

            $query = $result->queryAll();
            //var_dump($query);die;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_code' => SORT_ASC],
                'attributes' => [
                    'vendor_code',
                    'union_code',
                    'U_Short_Name',
                    'DCS_Installed',
                    'InActiveD_DCS',
                    'Data_Received_Dcs',
                    'Network_Available_Data_Not_Received',
                    'DCS_Under_Maintanance',
                    'No_Network_Non_Functioning',
                ],
            ],
        ]);

        return $this->render('dpmcu_working_status', ['model' => $model, 'dataProvider' => $dataProvider, 'summary' => true]);
    }

    public function actionCleaningNotDone() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Union_Shiftwise_Summary(:Date1,:Date2,:Union_Code,:shift)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':shift', !empty($model->shift) ? $model->shift : 3);
            $query = $result->queryAll();
            //var_dump($query);exit;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'dtdate',
                    'qty',
                    'avg_fat',
                    'avg_snf',
                    'kg_fat',
                    'kg_snf',
                    'avg_rate',
                    'total_amount',
                ],
            ],
        ]);

        return $this->render('cleaning_not_done', ['model' => $model, 'dataProvider' => $dataProvider, 'summary' => true]);
    }

    public function actionCalibrationChangeReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblMilkCollectionSearch();
        $model->load($request);
        $model->scenario = 'union_count';
        $start_date = !empty($request['min_date']) ? date('Y-m-d', strtotime($request['min_date'])) : NULL;
        $end_date = !empty($request['max_date']) ? date('Y-m-d', strtotime($request['max_date'])) : NULL;

        if (empty($model->union_code)) {
            $model->union_code = Yii::$app->session->get('Unions');
        }
        $query = [];
        if (!empty($start_date) && !empty($end_date)) {
            $result = \Yii::$app->db->createCommand("{CALL rpt_MIS_Union_Shiftwise_Summary(:Date1,:Date2,:Union_Code,:shift)}")
                    ->bindValue(':Union_Code', $model->union_code)
                    ->bindValue(':Date1', $start_date . ' 06:00:00 AM')
                    ->bindValue(':Date2', $end_date . ' 18:00:00 PM')
                    ->bindValue(':shift', !empty($model->shift) ? $model->shift : 3);
            $query = $result->queryAll();
            //var_dump($query);exit;
        }
        //print_r($query);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['union_name' => SORT_ASC],
                'attributes' => [
                    'union_code',
                    'union_name',
                    'dtdate',
                    'qty',
                    'avg_fat',
                    'avg_snf',
                    'kg_fat',
                    'kg_snf',
                    'avg_rate',
                    'total_amount',
                ],
            ],
        ]);

        return $this->render('calibration_change_report', ['model' => $model, 'dataProvider' => $dataProvider, 'summary' => true]);
    }

    public function actionShiftAReport() {

        $request = Yii::$app->request->queryParams;

        $model = new TblDpuRequestSearch();
        $model->load($request);
        $model->scenario = 'shift_a_report';
        $date = !empty($model->max_date) ? date('Y-m-d H:i:s', strtotime($model->max_date . ' ' . $model->shift)) : NULL;
        $query = [];
        if (!empty($date) && $model->validate()) {
            $result = \Yii::$app->db->createCommand("{CALL rptShiftA(:Date,:bmc)}")
                    ->bindValue(':Date', $date)
                    ->bindValue(':bmc', $model->bmc_code);
            $query = $result->queryAll();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['bmc_name' => SORT_ASC],
                'attributes' => [
                    'bmc_name',
                    'dcs_code',
                    'dcs_name',
                    'DPU_SerialNo',
                    'DPUVersionNo',
                    'MA_Internal_Number',
                    'MA_External_Number',
                    'Last_Calibration_Date',
                    'Last_Cleaning_Date'
                ],
            ],
        ]);

        return $this->render('shift_a_report', ['model' => $model, 'dataProvider' => $dataProvider, 'summary' => true]);
    }

}
