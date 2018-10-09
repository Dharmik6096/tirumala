<?php

namespace app\modules\report\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollection;

/**
 * TblMilkCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollection`.
 */
class TblMilkCollectionSearch extends TblMilkCollection {

    /**
     * @inheritdoc
     */
    public $union_code, $min_date, $max_date, $total_shift, $total_qty, $avg_fat, $avg_snf, $amount, $shift, $union_name, $dcs_name, $total_dcs, $dcs_code_ex, $shift_per, $disctrict_name, $MinDate, $MaxDate, $Summary, $dtdate, $kg_fat, $kg_snf, $avg_rate, $vendor, $plant_code, $mcc_code, $bmc_code;

    public function rules() {
        return [
            [['union_code'], 'required', 'except' => 'union_count'],
            [['plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => 'union_count'],
            [['min_date', 'max_date'], 'required'],
            [['dcs_code', 'max_date', 'shift', 'milk_type_code', 'vendor', 'plant_code', 'mcc_code', 'bmc_code'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'dcs_name' => Yii::t('app', 'Society Name'),
            'get_shift_data' => Yii::t('app', 'Total Received Data'),
            'total_qty' => Yii::t('app', 'Total Received Qty'),
            'avg_fat' => Yii::t('app', 'Avg. FAT'),
            'avg_snf' => Yii::t('app', 'Avg. SNF'),
            'amount' => Yii::t('app', 'Total Amount'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'total_dcs' => Yii::t('app', 'No of Societies'),
            'dcs_code_ex' => Yii::t('app', 'Old Soc. Code'),
            'shift_per' => Yii::t('app', 'Shift %'),
            'MinDate' => Yii::t('app', 'First Date of Receive'),
            'MaxDate' => Yii::t('app', 'Last Date of Receive'),
            'dtdate' => Yii::t('app', 'Date'),
            'kg_fat' => Yii::t('app', 'Kg. FAT'),
            'kg_snf' => Yii::t('app', 'Kg. SNF'),
            'avg_rate' => Yii::t('app', 'Avg. Rate'),
            'InActiveD_DCS' => Yii::t('app', 'In Active Dcs'),
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

        $query->joinWith(['dcsCode']);
        if (Yii::$app->session->get('Unions') !== '' && empty($this->union_code)) {
            $query->andFilterWhere([ 'tbl_dcs.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
            $query->andFilterWhere(['tbl_milk_collection.dcs_code' => $this->dcs_code]);
        } else {
            $query->andFilterWhere(['tbl_dcs.union_code' => $this->union_code]);
            $query->andFilterWhere(['tbl_milk_collection.dcs_code' => $this->dcs_code]);
        }


        if (!empty($request['min_date']) && !empty($request['max_date'])) {
            $start_date = date('Y-m-d', strtotime($request['min_date']));
            $end_date = date('Y-m-d', strtotime($request['max_date']));
            if ($start_date != $end_date)
                $query->andFilterWhere(['between', 'CAST(date_time_of_collection AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', $start_date]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->shift != 1) {
            $query->andFilterWhere(['like', 'shift', $this->shift]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'milk_collection_code' => $this->milk_collection_code,
            'milk_type_code' => $this->milk_type_code,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'water' => $this->water,
            'qty' => $this->qty,
            'rtpl' => $this->rtpl,
            'amount' => $this->amount,
            'date_time_of_collection' => $this->date_time_of_collection,
            'date_time_of_recieve' => $this->date_time_of_recieve,
            'sample_no' => $this->sample_no,
            'ack' => $this->ack,
        ]);

        $query->andFilterWhere(['like', 'member_code', $this->member_code])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'village_code', $this->village_code])
                ->andFilterWhere(['like', 'type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'rate_code', $this->rate_code])
                ->andFilterWhere(['like', 'error_log', $this->error_log])
                ->andFilterWhere(['like', 'soc_bmc_flag', $this->soc_bmc_flag]);
        echo $query->createCommand()->getRawSql();
        die;
        return $dataProvider;
    }

}
