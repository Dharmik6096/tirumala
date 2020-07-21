<?php

namespace app\modules\eipldpu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\eipldpu\models\TblEiplMasterFileLog;

/**
 * TblEiplMasterFileLogSearch represents the model behind the search form about `app\modules\eipldpu\models\TblEiplMasterFileLog`.
 */
class TblEiplMasterFileLogSearch extends TblEiplMasterFileLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_log_code', 'dpu_type', 'originating_type'], 'integer'],
            [['process_type', 'data_type', 'file_type', 'file_path', 'file_name', 'file_name_download', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ref_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblEiplMasterFileLog::find();

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
            'file_log_code' => $this->file_log_code,
            'dpu_type' => $this->dpu_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'process_type', $this->process_type])
            ->andFilterWhere(['like', 'data_type', $this->data_type])
            ->andFilterWhere(['like', 'file_type', $this->file_type])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_name_download', $this->file_name_download])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'ref_code', $this->ref_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
