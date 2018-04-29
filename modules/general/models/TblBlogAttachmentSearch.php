<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblBlogAttachment;

/**
 * TblBlogAttachmentSearch represents the model behind the search form about `app\modules\general\models\TblBlogAttachment`.
 */
class TblBlogAttachmentSearch extends TblBlogAttachment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'blog_id'], 'integer'],
            [['attachment_path'], 'safe'],
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
        $query = TblBlogAttachment::find();

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
            'id' => $this->id,
            'blog_id' => $this->blog_id,
        ]);

        $query->andFilterWhere(['like', 'attachment_path', $this->attachment_path]);

        return $dataProvider;
    }
}
