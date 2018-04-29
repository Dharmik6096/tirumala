<?php

namespace app\modules\installation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\installation\models\InstallationIdentity;

/**
 * InstallationIdentitySearch represents the model behind the search form about `app\modules\installation\models\InstallationIdentity`.
 */
class InstallationIdentitySearch extends InstallationIdentity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_delete', 'is_active'], 'integer'],
            [['originating_org_id', 'originating_org_type', 'organization_code', 'organization_type', 'installed', 'db_path', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp'], 'safe'],
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
        $query = InstallationIdentity::find();

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
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'originating_org_id', Yii::$app->session->get('organizations_code')])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'organization_code', $this->organization_code])
            ->andFilterWhere(['like', 'organization_type', $this->organization_type])
            ->andFilterWhere(['like', 'installed', $this->installed])
            ->andFilterWhere(['like', 'db_path', $this->db_path]);

        return $dataProvider;
    }
}
