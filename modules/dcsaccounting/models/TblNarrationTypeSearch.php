<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblNarrationType;

/**
 * TblNarrationTypeSearch
 */
class TblNarrationTypeSearch extends TblNarrationType
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['narration_type_code', 'union_code', 'narration_type', 'narration_type_local'], 'safe'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblNarrationType::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_narration_type', 'tbl_narration_type', 'tbl_narration_type', 'tbl_narration_type');

        $query->andFilterWhere(['like', 'tbl_narration_type.narration_type_code', $this->narration_type_code])
              ->andFilterWhere(['like', 'tbl_narration_type.narration_type', $this->narration_type])
              ->andFilterWhere(['like', 'tbl_narration_type.narration_type_local', $this->narration_type_local]);

        return $dataProvider;
    }
}
