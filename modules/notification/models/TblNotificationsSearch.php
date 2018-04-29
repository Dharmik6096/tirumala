<?php

namespace app\modules\notification\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\notification\models\TblNotifications;

/**
 * TblNotificationsSearch represents the model behind the search form about `app\modules\notification\models\TblNotifications`.
 */
class TblNotificationsSearch extends TblNotifications
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['id','type','title', 'msg', 'msg_by', 'attachment', 'publish_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblNotifications::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['publish_date'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(!empty($this->publish_date))
            $query->andwhere(['publish_date' => date('Y-m-d', strtotime($this->publish_date))]);
        
        Yii::$app->general->filterByNumber($query,$this,['id']);

        // grid filtering conditions
        $query->andFilterWhere([
//            'id' => $this->id,
//            'publish_date' => $this->publish_date,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'msg', $this->msg])
            ->andFilterWhere(['like', 'msg_by', $this->msg_by])
            ->andFilterWhere(['like', 'attachment', $this->attachment])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
