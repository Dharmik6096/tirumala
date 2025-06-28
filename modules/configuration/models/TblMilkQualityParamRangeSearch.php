<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblMilkQualityParamRange;

/**
 * TblMilkQualityParamRangeSearch represents the model behind the search form about `app\modules\configuration\models\TblMilkQualityParamRange`.
 */
class TblMilkQualityParamRangeSearch extends TblMilkQualityParamRange {

    public $quality_config_process_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr', 'milk_quality_param_range_code', 'animal_type_code', 'originating_type', 'process_name', 'union_code', 'org_code', 'org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'quality_config_process_name'], 'safe'],
                [['process_name', 'union_code', 'plant_code'], 'required', 'on' => ['qualityRange']],
                [['mcc_plant_code', 'bmc_code'], 'required', 'when' => function ($model) {
                    return $model->process_name == 'BMC_MILK_DISPATCH';
                }, 'message' => Yii::t('app/validation', '{attribute} cannot be blank.'),
                'whenClient' => "function (attribute, value) { 
                    return $('#tblmilkqualityparamrangesearch-process_name').val() == 'BMC_MILK_DISPATCH'; 
                }", 'on' => ['qualityRange']],
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
        $query = TblMilkQualityParamRange::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'animalTypeCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_quality_param_range', 'tbl_milk_quality_param_range', 'tbl_milk_quality_param_range');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'tbl_milk_quality_param_range.process_name', $this->quality_config_process_name])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->animal_type_code])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.min_fat', $this->min_fat])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.max_fat', $this->max_fat])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.min_snf', $this->min_snf])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.max_snf', $this->max_snf])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.min_clr', $this->min_clr])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.max_clr', $this->max_clr])
                ->andFilterWhere(['like', 'tbl_milk_quality_param_range.org_type', $this->org_type]);

        return $dataProvider;
    }

    public function searchData($params) {
        $query = TblMilkQualityParamRange::find();

        $this->load($params);

        if (!$this->validate()) {
            return new ActiveDataProvider([
                'query' => TblMilkQualityParamRange::find()->where('1=0'), // No data if validation fails
            ]);
        }
        $query->andWhere(['process_name' => $this->process_name, 'union_code' => $this->union_code]);
        if ($this->process_name == 'BMC_MILK_DISPATCH') {
            $query->andWhere(['org_code' => $this->bmc_code, 'org_type' => 'BMC']);
        } else {
            $query->andWhere(['org_code' => $this->plant_code, 'org_type' => 'PLANT']);
        }
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
