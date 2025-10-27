<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblCollectionDataAlias;
use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\ArrayDataProvider;

/**
 * TblBmcCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblBmcCollection`.
 */
class TblBmcCollectionSearch extends TblBmcCollection {

    public $from_date, $to_date, $from_shift, $to_shift, $ref_code, $bmc_ref_code;
    public $f_union_code, $f_plant_code, $f_mcc_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_collection_code', 'milk_type_code', 'sample_no', 'ack'], 'integer'],
                [['can_no', 'no_of_can', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'remarks', 'from_date', 'to_date', 'from_shift', 'to_shift', 'qty_mode', 'doc_no', 'bmc_silos_info_code', 'route_code'], 'safe'],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount'], 'number'],
                [['mcc_plant_code', 'plant_code', 'union', 'customer_code', 'customer_type', 'customer_name', 'bmc_code', 'originating_org_type', 'originating_type', 'ref_code', 'bmc_ref_code', 'converted_amount'], 'safe'],
                [['union_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['deleteMilkCollection']],
                [['f_union_code', 'f_plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['shiftLock']],
                [['f_union_code', 'f_plant_code', 'f_mcc_code'], 'safe'],
                [['scheme_rate', 'scheme_rate_code', 'actual_rate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblBmcCollection::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions


        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'tbl_bmc_collection.date_time_of_collection', $from_date]);

        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.ref_code', $this->ref_code], ['like', 'tbl_customer_master.ref_code', $this->ref_code]]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'tbl_bmc_collection.date_time_of_collection', $to_date]);

        $query->join('LEFT JOIN', 'tbl_dcs', 'tbl_dcs.dcs_code = tbl_bmc_collection.customer_code');
        $query->join('LEFT JOIN', 'tbl_customer_master', 'tbl_customer_master.customer_code = tbl_bmc_collection.customer_code');
        $query->join('LEFT JOIN', 'tbl_customer_type', 'tbl_customer_type.customer_type = tbl_bmc_collection.customer_type AND tbl_customer_type.union_code = tbl_bmc_collection.union_code');
        $query->join('LEFT JOIN', 'tbl_bmc', 'tbl_bmc.bmc_code = tbl_bmc_collection.bmc_code');
        $query->join('LEFT JOIN', 'tbl_bmc_silos_info', 'tbl_bmc_silos_info.bmc_silos_info_code = tbl_bmc_collection.bmc_silos_info_code');
//        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'mainBmcCode', 'silosCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_collection', 'tbl_bmc_collection', 'tbl_bmc_collection');

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['and', ['>=', 'tbl_bmc_collection.date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 00:00:00.000'], ['<=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 23:59:59.000']]);

        $query->andFilterWhere([
            'tbl_bmc_collection.qty_mode' => $this->qty_mode,
            'tbl_bmc_collection.doc_no' => $this->doc_no,
            'tbl_bmc_collection.sample_no' => $this->sample_no,
//            'tbl_bmc_collection.originating_type' => $this->originating_type,
            'tbl_bmc_collection.route_code' => $this->route_code,
        ]);
        if (isset($this->originating_type)) {
            if ($this->originating_type == 5) {
                $query->andFilterWhere(['tbl_bmc_collection.originating_type' => 0]);
            } elseif ($this->originating_type == 1) {
                $query->andFilterWhere(['tbl_bmc_collection.originating_type' => 1]);
            } elseif ($this->originating_type == 2) {
                $query->andFilterWhere(['tbl_bmc_collection.originating_type' => [11, 12, 21, 23]]);
            } elseif ($this->originating_type == 3) {
                $query->where('0=1');
            } elseif ($this->originating_type == 4) {
                $query->andFilterWhere(['tbl_bmc_collection.originating_type' => [13, 22]]);
            }
        }
        $query->andFilterWhere(['like', 'tbl_bmc_collection.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.rtpl', $this->rtpl])
                ->andFilterWhere(['like', 'tbl_bmc_collection.can_no', $this->can_no])
                ->andFilterWhere(['like', 'tbl_bmc_collection.no_of_can', $this->no_of_can])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_bmc_collection.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'tbl_bmc_silos_info.silo_no', $this->bmc_silos_info_code])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_ref_code]);
        // $query->orderBy(['tbl_bmc_collection.date_time_of_collection' => SORT_DESC, 'tbl_bmc.bmc_name' => SORT_ASC, 'tbl_bmc_collection.doc_no' => SORT_ASC, 'tbl_bmc_collection.sample_no' => SORT_ASC]);
        return $dataProvider;
    }

    public function bmcwisesearch($params) {
        $query = TblBmcCollection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mcc_plant_code' => $this->mcc_plant_code,
            'date_time_of_collection' => $this->date_time_of_collection,
            'shift_code' => $this->shift_code,
        ]);

        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);

        $query = TblBmcCollection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        $query->joinWith(['dcsCode']);
//        $query->andWhere(['tbl_dcs.mcc_plant_code' => $this->mcc_plant_code]);

        Yii::$app->general->filterByOrg($query, $this);

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);
        $query->andFilterWhere(['shift_code' => $this->shift_code]);
//        $query->andFilterWhere(['like', 'tbl_bmc_collection.dcs_code', $this->dcs_code]);
        $query->andFilterWhere(['like', 'tbl_bmc_collection.bmc_code', $this->bmc_code]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $this->load($params);
        $ApprovalData = TblCollectionDataAlias::find()->select(['union_code', 'bmc_code', 'dcs_code', 'customer_code', 'customer_type', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty', 'fat', 'snf', 'clr', 'rtpl', 'date_time_of_collection', 'shift_code', 'amount', 'no_of_can', 'qty_mode', 'converted_qty', 'converted_amount', 'doc_no', 'status' => new Expression("'Not Verified'"), 'route_code', 'can_no', 'antibiotic', 'scheme_rate', 'scheme_rate_code', 'actual_rate'])
                ->where(['bmc_code' => $this->bmc_code, 'shift_code' => $this->shift_code, 'action_perform' => 'CREATE', 'table_name' => 'tbl_bmc_collection'])
                ->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query = $this->find()->select(['tbl_bmc_collection.union_code', 'bmc_code', 'dcs_code', 'customer_code', 'customer_type', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty', 'fat', 'snf', 'clr', 'rtpl', 'date_time_of_collection', 'shift_code', 'amount', 'no_of_can', 'qty_mode', 'converted_qty', 'converted_amount', 'doc_no', 'status' => new Expression("'Verified'"), 'route_code', 'can_no', 'antibiotic', 'scheme_rate', 'scheme_rate_code', 'actual_rate']);

        $unionQuery = (new ActiveQuery(TblBmcCollection::className()))->from([
                    'tbl_bmc_collection' => $query->union($ApprovalData, TRUE)
                ])->orderBy(['sample_no' => SORT_DESC]);

        // add conditions that should always apply here,'

        $query->joinWith(['mccPlantCode.plantCode']);
        $query->andWhere([
            'tbl_bmc_collection.bmc_code' => $this->bmc_code]);

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);
        $query->andFilterWhere(['shift_code' => $this->shift_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function updatesarch($params) {
        $this->load($params);
        $query = TblBmcCollection::find();
        // ->where(['IS NOT', 'tbl_bmc_collection.bmc_code', NULL]);
        // add conditions that should always apply here


        $query->joinWith(['mccPlantCode.plantCode']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (empty($this->from_date)) {
            $this->from_date = date('d-m-Y');
            $this->from_shift = 1;
        }
        if (empty($this->to_date)) {
            $this->to_date = date('d-m-Y');
            $this->to_shift = 2;
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }

        $query->andFilterWhere(['tbl_bmc_collection.dcs_code' => $this->dcs_code]);
        $query->andFilterWhere(['tbl_bmc_collection.customer_code' => $this->customer_code]);
        $query->andFilterWhere(['tbl_bmc_collection.customer_type' => $this->customer_type]);

        $query->andWhere([
            'tbl_bmc_collection.bmc_code' => $this->bmc_code,
            'tbl_bmc_collection.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_bmc_collection.plant_code' => $this->plant_code,
            'tbl_bmc_collection.union_code' => $this->union_code,
        ]);
        $query->andWhere(['IS NOT', 'tbl_bmc_collection.bmc_code', NULL]);

        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $query = TblBmcCollection::find()->where(['IS NOT', 'tbl_bmc_collection.bmc_code', NULL]);
        // add conditions that should always apply here

        $query->joinWith(['mccPlantCode.plantCode']);
        $flag = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'collection_approval', 'PORTAL');
        if (in_array($flag, [1, 2])) {
            $query->join('LEFT JOIN', 'tbl_collection_data_alias', "tbl_collection_data_alias.bmc_code = tbl_bmc_collection.bmc_code and tbl_collection_data_alias.customer_code = tbl_bmc_collection.customer_code and tbl_collection_data_alias.customer_type = tbl_bmc_collection.customer_type and tbl_collection_data_alias.old_milk_type_code = tbl_bmc_collection.milk_type_code and tbl_collection_data_alias.old_milk_quality_type_code = tbl_bmc_collection.milk_quality_type_code and tbl_collection_data_alias.shift_code = tbl_bmc_collection.shift_code and tbl_collection_data_alias.date_time_of_collection = tbl_bmc_collection.date_time_of_collection and tbl_collection_data_alias.table_name = 'tbl_bmc_collection' and tbl_collection_data_alias.action_perform = 'DELETE'");
        }
        $query->join('LEFT JOIN', 'tbl_mcc_shift_lock', 'tbl_mcc_shift_lock.mcc_plant_code = tbl_bmc_collection.mcc_plant_code and CAST(tbl_mcc_shift_lock.date_time_of_collection as date) = CAST(tbl_bmc_collection.date_time_of_collection as date) and tbl_mcc_shift_lock.shift_code = tbl_bmc_collection.shift_code and tbl_mcc_shift_lock.bmc_lock = 1');

        $query->andWhere([
            'tbl_bmc_collection.bmc_code' => $this->bmc_code]);

        if (empty($this->from_date)) {
            $this->from_date = date('d-m-Y');
            $this->from_shift = 1;
        }
        if (empty($this->to_date)) {
            $this->to_date = date('d-m-Y');
            $this->to_shift = 2;
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_bmc_collection.date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'tbl_bmc_collection.date_time_of_collection', $to_date]);
        }

        $query->andFilterWhere(['tbl_bmc_collection.dcs_code' => $this->dcs_code]);
        if (in_array($flag, [1, 2])) {
            $query->andWhere(['or', ['is', 'tbl_collection_data_alias.bmc_code', NULL], ['is', 'tbl_collection_data_alias.shift_code', NULL], ['is', 'tbl_collection_data_alias.customer_code', NULL], ['is', 'tbl_collection_data_alias.customer_type', NULL], ['is', 'tbl_collection_data_alias.date_time_of_collection', NULL]]);
        }
//        $query->andwhere(['tbl_collection_data_alias.action_perform' => 'DELETE', 'tbl_collection_data_alias.table_name' => 'collectionvillage']);
        $query->andWhere(['or', ['is', 'tbl_mcc_shift_lock.mcc_plant_code', NULL], ['is', 'tbl_mcc_shift_lock.shift_code', NULL], ['is', 'tbl_mcc_shift_lock.date_time_of_collection', NULL]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->andFilterWhere(['tbl_bmc_collection.customer_code' => $this->customer_code]);
        $query->andFilterWhere(['tbl_bmc_collection.customer_type' => $this->customer_type]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function shiftlocksearch($params) {
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'f_union_code' => '',
                'f_plant_code' => '',
                'f_mcc_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => ''];

            $sp_params = array_merge($sp_params, $params['TblBmcCollectionSearch']);
            if (empty($this->f_mcc_code)) {
                $this->f_mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
            }
            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            $sp_params['f_mcc_code'] = $this->f_mcc_code;
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);
            $sp = 'portal_mcc_shift_lock_data';
            $output = \Yii::$app->general->getSpData($sp, $sp_params);
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

}
