<?php

namespace app\modules\report\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\report\models\TblDpuRequest;

/**
 * TblDpuRequestSearch represents the model behind the search form about `app\modules\report\models\TblDpuRequest`.
 */
class TblDpuRequestSearch extends TblDpuRequest
{
    
    public $union_code,$min_date,$max_date,$shift, $union_name,$total_dcs,$dpu_dcs,$col_dcs,$no_col_dcs,$dcs_name,$dcs_code_ex;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_code', 'shift_code'], 'integer'],
            [['dcs_code', 'datetime', 'request_date', 'request_time', 'union_code'], 'safe'],
            [['lat', 'long'], 'number'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'dcs_name' => Yii::t('app', 'Society Name'),
            'dcs_code'=>Yii::t('app', 'Society Code'),
            'total_dcs'=>Yii::t('app', 'No of Societies'),
            'dpu_dcs'=>Yii::t('app', 'DPU Installed'),
            'col_dcs'=>Yii::t('app', 'Collection Received'),
            'no_col_dcs'=>Yii::t('app', 'Collection Not Received'),
            'dcs_code_ex'=>Yii::t('app', 'Old Soc. Code'),
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
        $query = TblDpuRequest::find();

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
            'request_code' => $this->request_code,
            'shift_code' => $this->shift_code,
            'datetime' => $this->datetime,
            'request_date' => $this->request_date,
            'request_time' => $this->request_time,
            'lat' => $this->lat,
            'long' => $this->long,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
