<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblBanner;

/**
 * TblBannerSearch represents the model behind the search form about `app\modules\general\models\TblBanner`.
 */
class TblBannerSearch extends TblBanner {

    public $login_type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['login_type', 'union_code', 'from_date', 'to_date', 'title', 'banner_for', 'description', 'tap_operation', 'tap_event', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'banner_code', 'seq_no', 'originating_type'], 'safe'],
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
        $query = TblBanner::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_banner');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->login_type)) {
            $query->joinWith(['bannerApplicabilityCode']);

            $query->andFilterWhere(['tbl_banner_applicability.department' => $this->department]);
        }
        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_banner.from_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_banner.to_date', $to_date]);
        }

        $query->andFilterWhere(['like', 'tbl_banner.title', $this->title])
                ->andFilterWhere(['like', 'tbl_banner.banner_for', $this->banner_for])
                ->andFilterWhere(['like', 'tbl_banner.description', $this->description])
                ->andFilterWhere(['like', 'tbl_banner.tap_event', $this->tap_event])
                ->andFilterWhere(['like', 'tbl_banner.seq_no', $this->seq_no]);

        return $dataProvider;
    }

}
