<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcsBmc;

/**
 * TblDcsBmcSearch represents the model behind the search form about `app\modules\organisation\models\TblDcsBmc`.
 */
class TblDcsBmcSearch extends TblDcsBmc {
    
    public $mobile_no;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_code', 'mcc_plant_code', 'bmc_name', 'created_at', 'is_active', 'capacity', 'manufacturer_code', 'bmc_milk_type', 'model', 'updated_at', 'created_by', 'updated_by', 'subcenter_code', 'union_code', 'valid_from', 'local_name', 'is_weight_manual', 'is_quality_manual', 'is_rented_bmc', 'mobile_no'], 'safe'],
            [['bmc_code_ex', 'ref_code', 'rate_calculate_on_merge', 'x_col1', 'aadhaar_no', 'pincode', 'address'], 'safe'],
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

        $query = $this->find();
        $query->distinct('tbl_bmc.bmc_code');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['bmc_name' => SORT_ASC]],
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc', 'tbl_bmc', 'tbl_bmc');


        if (Yii::$app->session->get('BMC') !== '')
            $query->andFilterWhere(['tbl_bmc.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'contactDetails', 'plantCode', 'channelMaster']);
        if (!empty($this->mcc_plant_code)) {
            $query->joinWith(['tblMccPlant']);
            $query->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_plant_code]);
        }
        if (!empty($this->manufacturer_code)) {
            $query->joinWith(['manufacturerCode']);
            $query->andFilterWhere(['like', 'tbl_manufacturer.manufacturer_name', $this->manufacturer_code]);
        }
//        if(!empty($this->capacity)){
//            $query->joinWith(['capacity0']);
//            $query->andFilterWhere([
//            'tbl_capacity.value'=> $this->capacity
//        ]);
//        }
        if (!empty($this->capacity)) {
            $query->joinWith(['capacity0']);
            if (preg_match('/^[1-9][0-9]*$/', $this->capacity)) {
                $query->andFilterWhere(['tbl_capacity.value' => $this->capacity]);
            } else {
                $query->andFilterWhere(['like', 'tbl_capacity.value', $this->capacity]);
            }
        }
        if (!empty($this->bmc_milk_type)) {
            $query->joinWith(['bmcMilkType']);
            $query->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->bmc_milk_type]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bmc.is_active' => $this->is_active,
            'tbl_bmc.is_weight_manual' => $this->is_weight_manual,
            'tbl_bmc.is_quality_manual' => $this->is_quality_manual,
            'tbl_bmc.rate_calculate_on_merge' => $this->rate_calculate_on_merge,
            'tbl_bmc.is_rented_bmc' => $this->is_rented_bmc,
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc.bmc_code_ex', $this->bmc_code_ex])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_name])
                ->andFilterWhere(['like', 'tbl_bmc.local_name', $this->local_name])
                ->andFilterWhere(['like', 'tbl_bmc.model', $this->model])
                ->andFilterWhere(['like', 'tbl_bmc.subcenter_code', $this->subcenter_code])
                ->andFilterWhere(['like', 'tbl_channel_master.channel_desc', $this->x_col1])
                ->andFilterWhere(['like', 'tbl_bmc.address', $this->address])
                ->andFilterWhere(['like', 'tbl_bmc.pincode', $this->pincode])
                ->andFilterWhere(['like', 'tbl_bmc.aadhaar_no', $this->aadhaar_no])
                ->andFilterWhere(['like', 'tbl_contact_details.mobile_no', $this->mobile_no]);


        return $dataProvider;
    }

    public function bccSearch($params) {
        $query = $this->find();
        $this->load($params);
        $query->where(['mcc_plant_code' => $this->mcc_plant_code]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['bmc_name' => SORT_ASC]],
        ]);
        return $dataProvider;
    }

}
