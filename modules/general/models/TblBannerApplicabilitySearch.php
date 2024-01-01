<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblBannerApplicability;

/**
 * TblBannerApplicabilitySearch represents the model behind the search form about `app\modules\general\models\TblBannerApplicabilitySearch`.
 */
class TblBannerApplicabilitySearch extends TblBannerApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['login_type', 'banner_code', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblBannerApplicability::find();

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

        $query->andFilterWhere(['like', 'login_type', $this->login_type])
                ->andFilterWhere(['like', 'banner_code', $this->banner_code]);

        return $dataProvider;
    }

}
