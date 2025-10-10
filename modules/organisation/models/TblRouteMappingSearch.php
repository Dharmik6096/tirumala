<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblRouteMapping;

/**
 * TblRouteMappingSearch represents the model behind the search form about `app\modules\organisation\models\TblRouteMapping`.
 */
class TblRouteMappingSearch extends TblRouteMapping {

    public $unit;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['route_code', 'morning_start_time', 'morning_end_time', 'route_name', 'union_code', 'local_name', 'evening_start_time', 'evening_end_time', 'route_type', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_at', 'created_by', 'updated_at', 'updated_by', 'unit', 'valid_from'], 'safe'],
                [['capacity', 'vehicle_type_code', 'is_active'], 'integer'],
                [['route_length_kms'], 'number'],
                [['route_code_ex', 'ref_code', 'sap_route_code'], 'safe'],
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
        $query = TblRouteMapping::find();
        $query->distinct('tbl_route_mapping.route_code');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);
//        $query->joinWith(['dcsCode', 'mccCode', 'dcsBmcCode']);
//        Yii::$app->general->filterByOrg($query, $this, '', 'tbl_mcc_plant', 'tbl_bmc');
        $query->leftJoin('tbl_dcs', 'tbl_dcs.route_code = tbl_route_mapping.route_code');
        $query->leftJoin('tbl_mcc_plant', 'tbl_mcc_plant.mcc_plant_code = tbl_route_mapping.to_dest AND UPPER(tbl_route_mapping.to_type) = \'MCC\'');
        $query->leftJoin('tbl_bmc', 'tbl_bmc.bmc_code = tbl_route_mapping.to_dest AND UPPER(tbl_route_mapping.to_type) = \'BMC\'');

        $model_class = (new \ReflectionClass($this))->getShortName();
        $q_param = Yii::$app->request->queryParams;
        if (isset($q_param[$model_class])) {
            $data = $q_param[$model_class];
            isset($data['f_union_code']) ? $this->f_union_code = $data['f_union_code'] : NULL;
            isset($data['f_plant_code']) ? $this->f_plant_code = $data['f_plant_code'] : NULL;
            isset($data['f_mcc_code']) ? $this->f_mcc_code = $data['f_mcc_code'] : NULL;
            isset($data['f_bmc_code']) ? $this->f_bmc_code = $data['f_bmc_code'] : NULL;
            isset($data['f_dcs_code']) ? $this->f_dcs_code = $data['f_dcs_code'] : NULL;
        }
        $tablename = $this->tableSchema->fullName;
        $union_table = !empty($union_table) ? $union_table : $tablename;
        if (Yii::$app->session->get('Unions') !== '')
            $query->andFilterWhere([$union_table . '.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        if (!empty($this->f_union_code))
            $query->andFilterWhere([$union_table . '.union_code' => $this->f_union_code]);

        $plant_table = 'tbl_mcc_plant';
        if (Yii::$app->session->get('Plant') !== '') {
//            $query->andFilterWhere(['tbl_mcc_plant.plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
            $plantArr = explode(',', Yii::$app->session->get('Plant'));
            $query->andFilterWhere([
                'or',
                    ['tbl_mcc_plant.plant_code' => $plantArr, 'UPPER(tbl_route_mapping.to_type)' => 'MCC'],
                    ['tbl_bmc.plant_code' => $plantArr, 'UPPER(tbl_route_mapping.to_type)' => 'BMC']
            ]);
        }
        if (!empty($this->f_plant_code) && empty($this->f_bmc_code)) {
//            $query->andFilterWhere(['tbl_mcc_plant.plant_code' => $this->f_plant_code]);
            $query->andFilterWhere([
                'or',
                    ['tbl_mcc_plant.plant_code' => $this->f_plant_code, 'UPPER(tbl_route_mapping.to_type)' => 'MCC'],
                    ['tbl_bmc.plant_code' => $this->f_plant_code, 'UPPER(tbl_route_mapping.to_type)' => 'BMC']
            ]);
        }


//        if (Yii::$app->session->get('MCC') !== '')
//            $query->andFilterWhere(['tbl_mcc_plant.mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        if (!empty($this->f_mcc_code) && empty($this->f_bmc_code)) {
            $query->andFilterWhere(['or', ['tbl_route_mapping.to_dest' => $this->f_mcc_code, 'UPPER(tbl_route_mapping.to_type)' => 'MCC'], ['tbl_bmc.mcc_plant_code' => $this->f_mcc_code, 'UPPER(tbl_route_mapping.to_type)' => 'BMC']]);
        }


//        if (Yii::$app->session->get('BMC') !== '')
//            $query->andFilterWhere(['tbl_bmc.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        if (!empty($this->f_bmc_code)) {
            $query->andFilterWhere(['tbl_route_mapping.to_dest' => $this->f_bmc_code]);
        }



        $where_bmc = [];
        if (Yii::$app->session->get('BMC') !== '') {
            $where_bmc['tbl_route_mapping.to_dest'] = explode(',', Yii::$app->session->get('BMC'));
            //$where_bmc['tbl_bmc.bmc_code'] = explode(',', Yii::$app->session->get('BMC'));
            $where_bmc['UPPER(tbl_route_mapping.to_type)'] = 'bmc';
        }

        $where_mcc = [];
        if (Yii::$app->session->get('MCC') !== '') {
            $where_mcc['tbl_route_mapping.to_dest'] = explode(',', Yii::$app->session->get('MCC'));
            // $where_mcc['tbl_mcc_plant.mcc_plant_code'] = explode(',', Yii::$app->session->get('MCC'));
            $where_mcc['UPPER(tbl_route_mapping.to_type)'] = 'mcc';

            if (Yii::$app->session->get('BMC') == '') {
//                $where_bmc['tbl_route_mapping.to_dest'] = explode(',', Yii::$app->session->get('BMC'));
                $where_bmc['tbl_bmc.mcc_plant_code'] = explode(',', Yii::$app->session->get('MCC'));
                $where_bmc['UPPER(tbl_route_mapping.to_type)'] = 'bmc';
            }
        }
        $query->andWhere(['or', $where_bmc, $where_mcc]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->capacity)) {
            $query->joinWith(['capacity0']);
            $query->andFilterWhere([
                'tbl_capacity.value' => $this->capacity
            ]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            //'capacity' => $this->capacity,
            'tbl_route_mapping.route_length_kms' => $this->route_length_kms,
            'tbl_route_mapping.vehicle_type_code' => $this->vehicle_type_code,
            'tbl_route_mapping.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_route_mapping.route_code_ex', $this->route_code_ex])
                ->andFilterWhere(['like', 'tbl_route_mapping.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_code', $this->route_code])
                ->andFilterWhere(['like', 'tbl_route_mapping.morning_start_time', $this->morning_start_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.morning_end_time', $this->morning_end_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_name])
                ->andFilterWhere(['like', 'tbl_route_mapping.local_name', $this->local_name])
                ->andFilterWhere(['like', 'tbl_route_mapping.evening_start_time', $this->evening_start_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.evening_end_time', $this->evening_end_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_type', $this->route_type])
                ->andFilterWhere(['like', 'tbl_route_mapping.from_type', $this->from_type])
                ->andFilterWhere(['like', 'tbl_route_mapping.from_dest', $this->from_dest])
                ->andFilterWhere(['like', 'UPPER(tbl_route_mapping.to_type)', $this->to_type])
                ->andFilterWhere(['like', 'tbl_route_mapping.sap_route_code', $this->sap_route_code])
                ->andFilterWhere(['like', 'tbl_route_mapping.to_dest', $this->to_dest]);

        return $dataProvider;
    }

}
