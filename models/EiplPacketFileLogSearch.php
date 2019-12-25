<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EiplPacketFileLog;

/**
 * EiplPacketFileLogSearch represents the model behind the search form about `app\models\EiplPacketFileLog`.
 */
class EiplPacketFileLogSearch extends EiplPacketFileLog {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_id', 'file_status', 'total_record', 'processed_record', 'source_type'], 'safe'],
            [['dcs_code', 'file_path', 'file_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date'], 'safe'],
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
        $query = EiplPacketFileLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this);
        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(created_at as date)', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(created_at as date)', $to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'file_id' => $this->file_id,
            'file_status' => $this->file_status,
            'total_record' => $this->total_record,
            'processed_record' => $this->processed_record,
            'source_type' => $this->source_type,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'file_path', $this->file_path])
                ->andFilterWhere(['like', 'file_name', $this->file_name]);

        return $dataProvider;
    }

}
