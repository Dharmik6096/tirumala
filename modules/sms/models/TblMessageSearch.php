<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sms\models\TblMessage;

/**
 * TblMessageSearch
 */
class TblMessageSearch extends TblMessage
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'message_code', 'created_at', 'created_by', 'flg_sentbox_entry',
                'from_date', 'is_active', 'is_delete', 'message', 'message_local',
                'sync_status', 'sync_timestamp', 'to_date', 'updated_at', 'updated_by',
                'for_shift_code', 'from_shift', 'to_shift',
                'originating_org_code', 'originating_org_type',
                'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = TblMessage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_message', 'tbl_message', 'tbl_message', 'tbl_message');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'from_date', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'to_date', $to_date]);

        $query->andFilterWhere([
            'tbl_message.is_active'       => $this->is_active,
            'tbl_message.for_shift_code'  => $this->for_shift_code,
        ]);

        $query->andFilterWhere(['or', ['tbl_message.is_delete' => 0], ['tbl_message.is_delete' => null]]);

        $query->andFilterWhere(['like', 'tbl_message.message_code', $this->message_code])
            ->andFilterWhere(['like', 'tbl_message.message', $this->message])
            ->andFilterWhere(['like', 'tbl_message.message_local', $this->message_local])
            ->andFilterWhere(['like', 'tbl_message.dcs_code', $this->dcs_code]);

        return $dataProvider;
    }
}
