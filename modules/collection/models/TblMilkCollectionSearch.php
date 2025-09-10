<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollection;
use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\ArrayDataProvider;

/**
 * TblMilkCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollection`.
 */
class TblMilkCollectionSearch extends TblMilkCollection {

    /**
     * @inheritdoc
     */
    public $union_code;
    public $operator_fat, $operator_snf, $operator_qty, $operator_amount, $data_type_filter;
    public $from_date, $to_date, $from_shift, $to_shift, $sap_collection_type, $sap_data_post_status, $ref_code;

    public function rules() {
        return [
                [['milk_collection_code', 'sample_no', 'ack'], 'integer'],
                [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag', 'union_code', 'min_date', 'max_date', 'f_plant_code', 'f_mcc_code'], 'safe'],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'milk_type_code', 'operator_fat', 'operator_snf', 'operator_qty', 'operator_amount', 'from_date', 'to_date', 'from_shift', 'to_shift', 'sap_collection_type', 'sap_data_post_status', 'mcc_plant_code', 'bmc_code', 'ref_code', 'dcs_name', 'data_type_filter'], 'safe'],
                [['sap_collection_type'], 'required', 'on' => 'repostSapData'],
                [['protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'qty_mode', 'originating_org_type', 'originating_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'scheme_rate', 'actual_rate', 'scheme_rate_code'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['updateMilkCollection']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['deleteMilkCollection', 'bulkdeleteMilkCollection']],
                [['to_date'], 'validateToDate', 'on' => ['bulkdeleteMilkCollection']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['sap-upload']],
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
        $this->load($params);
        $query = TblMilkCollection::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);


        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        $query->joinWith(['dcsCode', 'memberCode', 'milkTypeCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->shift_code != 3) {
            $query->andFilterWhere(['like', 'shift_code', $this->shift_code]);
        }

        //  Yii::$app->general->filterByDropdownRange($query, $this, ['fat', 'snf', 'qty', 'amount']);
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_collection.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_collection.snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_milk_collection.qty', $this->qty]);
        }
        if (!empty($this->amount)) {
            $query->andFilterWhere([$this->operator_amount, 'tbl_milk_collection.amount', $this->amount]);
        }


        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_collection.milk_collection_code' => $this->milk_collection_code,
//            'milk_type_code' => $this->milk_type_code,
//            'fat' => $this->fat,
//            'snf' => $this->snf,
            'tbl_milk_collection.water' => $this->water,
//            'qty' => $this->qty,
//            'rtpl' => $this->rtpl,
//            'amount' => $this->amount,
//            'date_time_of_collection' => $this->date_time_of_collection,
            'tbl_milk_collection.date_time_of_recieve' => $this->date_time_of_recieve,
            'tbl_milk_collection.sample_no' => $this->sample_no,
            'tbl_milk_collection.ack' => $this->ack,
            'tbl_milk_collection.qty_mode' => $this->qty_mode,
//            'tbl_milk_collection.originating_type' => $this->originating_type,
        ]);
        if (isset($this->originating_type)) {
            if ($this->originating_type == 5) {
                $query->andFilterWhere(['tbl_milk_collection.originating_type' => 0]);
            } elseif ($this->originating_type == 1) {
                $query->andFilterWhere(['tbl_milk_collection.originating_type' => 1]);
            } elseif ($this->originating_type == 2) {
                $query->andFilterWhere(['tbl_milk_collection.originating_type' => [11, 12, 21, 23]]);
            } elseif ($this->originating_type == 3) {
                $query->where('0=1');
            } elseif ($this->originating_type == 4) {
                $query->andFilterWhere(['tbl_milk_collection.originating_type' => [13, 22]]);
            }
        }

        $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_milk_collection.name', $this->name])
                ->andFilterWhere(['like', 'tbl_milk_collection.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_milk_collection.auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'tbl_milk_collection.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_milk_collection.type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'tbl_milk_collection.purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'tbl_milk_collection.error_log', $this->error_log])
                ->andFilterWhere(['like', 'tbl_milk_collection.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_collection.protein', $this->protein])
                ->andFilterWhere(['like', 'tbl_milk_collection.density', $this->density])
                ->andFilterWhere(['like', 'tbl_milk_collection.lactose', $this->lactose])
                ->andFilterWhere(['like', 'tbl_milk_collection.incentive', $this->incentive])
                ->andFilterWhere(['like', 'tbl_milk_collection.total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'tbl_milk_collection.deduction', $this->deduction])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_milk_collection.originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name]);
//        echo $query->createCommand()->getRawSql();die;
        return $dataProvider;
    }

    public function createsearch($params) {
        $this->load($params);
//        $query = Collectionfarmer::find();

        $ApprovalData = TblCollectionDataAlias::find()->select(['mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty', 'fat', 'snf', 'clr', 'scheme_rate', 'actual_rate', 'rtpl', 'date_time_of_collection', 'shift_code', 'amount', 'status' => new Expression("'Not Verified'")])
                ->where(['mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'shift_code' => $this->shift_code, 'dcs_code' => $this->dcs_code, 'action_perform' => 'CREATE', 'table_name' => 'tbl_milk_collection'])
                ->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query = $this->find()->select(['tbl_milk_collection.mcc_plant_code', 'tbl_milk_collection.bmc_code', 'tbl_milk_collection.dcs_code', 'member_code', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty', 'fat', 'snf', 'clr', 'scheme_rate', 'actual_rate', 'rtpl', 'date_time_of_collection', 'shift_code', 'amount', 'status' => new Expression("'Verified'")]);


        $unionQuery = (new ActiveQuery(TblMilkCollection::className()))->from([
                    'tbl_milk_collection' => $query->union($ApprovalData, TRUE)
                ])->orderBy(['sample_no' => SORT_DESC]);

        // add conditions that should always apply here,'

        $query->joinWith(['mccPlantCode.plantCode', 'dcsCode']);
        $query->andWhere([
            'tbl_milk_collection.bmc_code' => $this->bmc_code,
            'tbl_milk_collection.dcs_code' => $this->dcs_code]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
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

    public function updatesarch($params, $collCodes = []) {
        $this->load($params);
        $query = TblMilkCollection::find();
        // add conditions that should always apply here

        if (!empty($collCodes)) {
            $org_string = "'" . implode(',', $collCodes) . "'";
            $command = Yii::$app->db->createCommand("SELECT distinct code from [SplitToTable](" . $org_string . ",',')");
            $org_codes = $command->sql;
            $query->andWhere('tbl_milk_collection.milk_collection_code in (' . $org_codes . ')');
//            $query->andWhere(['tbl_milk_collection.milk_collection_code' => $collCodes]);
        }

        $query->andWhere([
            'tbl_milk_collection.dcs_code' => $this->dcs_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $query->andWhere(['IS NOT', 'tbl_milk_collection.dcs_code', NULL]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
        } else {
            $from_date = date('Y-m-d');
            $from_shift = \Yii::$app->general->getshift(1);
            $from_date .= ' ' . $from_shift;
        }
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
        } else {
            $to_date = date('Y-m-d');
            $to_shift = \Yii::$app->general->getshift(2);
            $to_date .= ' ' . $to_shift;
        }
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);


        $query->andFilterWhere(['member_code' => $this->member_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->orderBy(['tbl_milk_collection.milk_collection_code' => SORT_DESC]);
        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $query = TblMilkCollection::find();

        // add conditions that should always apply here
        $flag = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'collection_approval', 'PORTAL');
        if (in_array($flag, [1, 2])) {
            // $query->joinWith(['approvalData']);
            $query->join('LEFT JOIN', 'tbl_collection_data_alias', "tbl_collection_data_alias.member_code = tbl_milk_collection.member_code and tbl_collection_data_alias.date_time_of_collection = tbl_milk_collection.date_time_of_collection and tbl_collection_data_alias.shift_code = tbl_milk_collection.shift_code and tbl_collection_data_alias.old_milk_type_code = tbl_milk_collection.milk_type_code and tbl_collection_data_alias.amount = tbl_milk_collection.amount and tbl_collection_data_alias.table_name = 'tbl_milk_collection' and tbl_collection_data_alias.action_perform = 'DELETE'");
        }
        $query->join('LEFT JOIN', 'tbl_mcc_shift_lock', 'tbl_mcc_shift_lock.mcc_plant_code = tbl_milk_collection.mcc_plant_code and CAST(tbl_mcc_shift_lock.date_time_of_collection as date) = CAST(tbl_milk_collection.date_time_of_collection as date) and tbl_mcc_shift_lock.shift_code = tbl_milk_collection.shift_code and tbl_mcc_shift_lock.member_lock = 1');

        $query->andWhere([
            'tbl_milk_collection.bmc_code' => $this->bmc_code]);

        if (empty($this->from_date)) {
            $this->from_date = date('d-m-Y');
        }
        if (empty($this->from_shift)) {
            $this->from_shift = 1;
        }
        if (empty($this->to_date)) {
            $this->to_date = date('d-m-Y');
        }
        if (empty($this->to_shift)) {
            $this->to_shift = 2;
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_milk_collection.date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift(strtoupper($this->to_shift));
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'tbl_milk_collection.date_time_of_collection', $to_date]);
        }

        $query->andFilterWhere(['tbl_milk_collection.member_code' => $this->member_code])
                ->andFilterWhere(['tbl_milk_collection.dcs_code' => $this->dcs_code]);
        if (in_array($flag, [1, 2])) {
            $query->andWhere(['or', ['is', 'tbl_collection_data_alias.member_code', NULL], ['is', 'tbl_collection_data_alias.bmc_code', NULL], ['is', 'tbl_collection_data_alias.dcs_code', NULL], ['is', 'tbl_collection_data_alias.shift_code', NULL], ['is', 'tbl_collection_data_alias.date_time_of_collection', NULL]]);
        }
        $query->andWhere(['or', ['is', 'tbl_mcc_shift_lock.mcc_plant_code', NULL], ['is', 'tbl_mcc_shift_lock.shift_code', NULL], ['is', 'tbl_mcc_shift_lock.date_time_of_collection', NULL]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function deletemembersearch($params) {
        $query = TblMilkCollection::find();

        // add conditions that should always apply here
        $this->setAttributes($params);

        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');
        if ($flag == 1) {
            $query->joinWith(['approvalData']);
        }
        $query->andWhere([
            'tbl_milk_collection.bmc_code' => $this->bmc_code]);

        $query->andFilterWhere(['tbl_milk_collection.date_time_of_collection' => $this->date_time_of_collection]);
        $query->andFilterWhere(['tbl_milk_collection.milk_type_code' => $this->milk_type_code]);
        $query->andFilterWhere(['tbl_milk_collection.dcs_code' => $this->dcs_code]);
        if ($flag == 1) {
            $query->andWhere(['or', ['is', 'tbl_collection_data_alias.member_code', NULL], ['is', 'tbl_collection_data_alias.bmc_code', NULL], ['is', 'tbl_collection_data_alias.dcs_code', NULL], ['is', 'tbl_collection_data_alias.shift_code', NULL], ['is', 'tbl_collection_data_alias.date_time_of_collection', NULL]]);
        }
        $query->orderBy(['tbl_milk_collection.sample_no' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }

    public function searchdcswisesummary($params) {
        //var_dump($params); exit;
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'dcs_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => ''
            ];
            if (empty($this->dcs_code)) {
                $this->dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
            }
            $sp_params = array_merge($sp_params, $params['TblMilkCollectionSearch']);
            $sp_params['dcs_code'] = is_array($params['TblMilkCollectionSearch']['dcs_code']) ? ',' . implode(',', $params['TblMilkCollectionSearch']['dcs_code']) . ',' : $params['TblMilkCollectionSearch']['dcs_code'];

            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);

            $output = \Yii::$app->general->getSpData('Portal_MilkCollection_dcs_wise_summary', $sp_params);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails

            $output = [];
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
                        'milk_type',
                        'qty',
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $fDate = date('Y-m-d', strtotime($this->from_date));
            $tDate = date('Y-m-d', strtotime($this->to_date));
            if ($tDate < $fDate) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
                return false;
            } else {
                $fDate = date_create($fDate);
                $tDate = date_create($tDate);
                $diff = date_diff($fDate, $tDate);
                $DayCount = $diff->format("%a");
                $DayCount = $DayCount + 1;
                if ($DayCount > 10) {
                    $this->addError('to_date', Yii::t('app/validation', 'Day Difference can not be greater than 10.'));
                    return false;
                }
            }
        }
    }

    public function searchsapupload($params) {
        //var_dump($params); exit;
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'dcs_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => '',
                'data_type_filter' => ''
            ];
            if (empty($this->dcs_code)) {
                $this->dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
            }
            $sp_params = array_merge($sp_params, $params['TblMilkCollectionSearch']);
            $sp_params['dcs_code'] = is_array($params['TblMilkCollectionSearch']['dcs_code']) ? ',' . implode(',', $params['TblMilkCollectionSearch']['dcs_code']) . ',' : $params['TblMilkCollectionSearch']['dcs_code'];

            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);

            $output = \Yii::$app->general->getSpData('Portal_MilkCollection_dcs_wise_summary_sap_upload', $sp_params);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails

            $output = [];
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

    public function detailmembersearch($params) {
        $query = TblMilkCollection::find();

        // add conditions that should always apply here
        $this->setAttributes($params);

        $query->andWhere([
            'tbl_milk_collection.bmc_code' => $this->bmc_code]);

        $query->andFilterWhere(['tbl_milk_collection.date_time_of_collection' => $this->date_time_of_collection]);

        $query->andFilterWhere(['tbl_milk_collection.dcs_code' => $this->dcs_code]);
        $query->orderBy(['tbl_milk_collection.sample_no' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
    
    public function repushsearch($params) {
        $this->load($params);
        $query = TblMilkCollection::find();

        $query->andWhere([
            'tbl_milk_collection.dcs_code' => $this->dcs_code,
            'tbl_milk_collection.shift_code' => $this->shift_code,
            'tbl_milk_collection.date_time_of_collection' => $this->date_time_of_collection]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
