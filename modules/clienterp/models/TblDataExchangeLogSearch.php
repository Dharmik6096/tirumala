<?php

namespace app\modules\clienterp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\clienterp\models\TblDataExchangeLog;
use app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails;
use app\modules\dcsoperation\models\TblMemberProvisional;

/**
 * TblDataExchangeLogSearch represents the model behind the search form about `app\modules\clienterp\models\TblDataExchangeLog`.
 */
class TblDataExchangeLogSearch extends TblDataExchangeLog {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['update_key', 'data_post_status', 'originating_type', 'process_name', 'process_code', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'resp_status', 'resp_desc', 'resp_msg', 'resp_param_1', 'resp_param_2', 'resp_param_3', 'resp_param_4', 'resp_param_5', 'resp_param_6'], 'safe'],
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
        $query = TblDataExchangeLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        $query->joinWith(['memberProvisionalCode']);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['=', new \yii\db\Expression('CAST(picked_datetime AS DATE)'), $this->picked_datetime]);
        $query->andFilterWhere(['=', new \yii\db\Expression('CAST(response_datetime AS DATE)'), $this->response_datetime]);


        $query->andFilterWhere(['like', 'update_key', $this->update_key])
            ->andFilterWhere(['like', 'data_post_status', $this->data_post_status])
            ->andFilterWhere(['like', 'originating_type', $this->originating_type])
            ->andFilterWhere(['like', 'process_name', $this->process_name])
            ->andFilterWhere(['like', 'process_code', $this->process_code])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'resp_status', $this->resp_status])
            ->andFilterWhere(['like', 'resp_desc', $this->resp_desc])
            ->andFilterWhere(['like', 'resp_msg', $this->resp_msg])
            ->andFilterWhere(['like', 'resp_param_1', $this->resp_param_1])
            ->andFilterWhere(['like', 'resp_param_2', $this->resp_param_2])
            ->andFilterWhere(['like', 'resp_param_3', $this->resp_param_3])
            ->andFilterWhere(['like', 'resp_param_4', $this->resp_param_4])
            ->andFilterWhere(['like', 'resp_param_5', $this->resp_param_5])
            ->andFilterWhere(['like', 'resp_param_6', $this->resp_param_6]);

        return $dataProvider;
    }


}
