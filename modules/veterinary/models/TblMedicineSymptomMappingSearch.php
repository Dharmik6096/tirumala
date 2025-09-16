<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_medicine_symptom_mapping".
 *
 * @property integer $medicine_symptom_id
 * @property integer $medicine_id
 * @property integer $symptom_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMedicineSymptomMappingSearch extends TblMedicineSymptomMapping {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_medicine_symptom_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'symptom_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
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
        $query = TblMedicineSymptomMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'medicine_id' => $this->medicine_id,
        ]);

        return $dataProvider;
    }

}
