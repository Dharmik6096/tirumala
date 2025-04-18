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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['memberProvisionalCode']);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
