<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblVCGMRGInfoTypeMaster;

/**
 * TblVCGMRGInfoTypeMasterSearch represents the model behind the search form about `app\modules\feedback\models\TblVCGMRGInfoTypeMaster`.
 */
class TblVCGMRGInfoTypeMasterSearch extends TblVCGMRGInfoTypeMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['info_type_id', 'originating_type'], 'integer'],
            [['info_type_desc', 'info_type_desc_local', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblVCGMRGInfoTypeMaster::find();

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
            'info_type_id' => $this->info_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'info_type_desc', $this->info_type_desc])
            ->andFilterWhere(['like', 'info_type_desc_local', $this->info_type_desc_local])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry]);

        return $dataProvider;
    }
}
