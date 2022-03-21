<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblLocationWiseKmDetail;

/**
 * TblLocationWiseKmDetailSearch represents the model behind the search form about `app\modules\transporter\models\TblLocationWiseKmDetail`.
 */
class TblLocationWiseKmDetailSearch extends TblLocationWiseKmDetail {

    public $from_date, $to_date, $f_union_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['km_detail_code', 'is_active'], 'integer'],
            [['from_type', 'from_dest', 'to_type', 'to_dest', 'wef_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['total_kms'], 'number'],
            [['f_union_code', 'from_date', 'to_date'], 'safe'],
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
//        $query = TblLocationWiseKmDetail::find()->select(['MAX(wef_date) as wef_date', 'from_type', 'to_type', 'from_dest', 'to_dest'])->groupBy(['from_type', 'to_type', 'from_dest', 'to_dest']);
        $subQuery = $this->find()
                ->select(['from_type', 'from_dest', 'to_type', 'to_dest', 'MAX(wef_date) AS wef_date', 'union_code'])
                ->groupBy(['from_type', 'from_dest', 'to_type', 'to_dest', 'union_code']);
        $query = $this->find()
                ->alias('t')
                ->select('t.*')
                ->innerJoin(['l' => $subQuery], 'l.from_type = t.from_type and l.from_dest = t.from_dest and l.to_type = t.to_type and l.to_dest = t.to_dest and l.wef_date = t.wef_date and l.union_code = t.union_code');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['plantCodeSource ps', 'plantCodeDest pd', 'mccPlantCodeSource ms', 'mccPlantCodeDest md', 'customerCodeSource cs', 'customerCodeDest cd']);
        Yii::$app->general->DeliveryChallanOrgFilter($query, 'tbl_location_wise_km_detail', 'from_dest', 'to_dest');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 't.wef_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $from_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 't.wef_date', $from_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'km_detail_code' => $this->km_detail_code,
        ]);


        $query->andFilterWhere(['like', 't.from_type', $this->from_type])
                ->andFilterWhere(['like', 't.from_dest', $this->from_dest])
                ->andFilterWhere(['like', 't.to_type', $this->to_type])
                ->andFilterWhere(['like', 't.total_kms', $this->total_kms])
                ->andFilterWhere(['like', 't.to_dest', $this->to_dest])
                ->andFilterWhere(['like', 't.union_code', $this->union_code])
                ->andFilterWhere(['like', 't.wef_date', ($this->wef_date == '') ? '' : Yii::$app->formatter->asDate($this->wef_date, 'php:Y-m-d')]);

        return $dataProvider;
    }

    public function detailsearch($params) {

        $query = TblLocationWiseKmDetail::find();
        $query->andWhere([ 'from_type' => $this->from_type, 'from_dest' => $this->from_dest, 'to_type' => $this->to_type, 'to_dest' => $this->to_dest]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}
