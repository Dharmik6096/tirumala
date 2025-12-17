<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_member_animal_type".
 *
 * @property integer $animal_type_code
 * @property string $animal_type_name
 * @property string $union_code
 * @property integer $is_active
 * @property integer $is_milch
 * @property string $local_name
 * @property string $short_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMemberAnimalTypeSearch extends TblMemberAnimalType {
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['animal_type_code', 'animal_type_name', 'is_active', 'is_milch', 'animal_type_name', 'union_code', 'local_name', 'short_name', 'created_by', 'originating_org_code', 'originating_org_type', 'updated_by', 'originating_type', 'created_at', 'updated_at',], 'safe'],
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
        $query = TblMemberAnimalType::find();

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
        $query->andFilterWhere(['like', 'animal_type_name', $this->animal_type_name]);

        return $dataProvider;
    }

}
