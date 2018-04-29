<?php

namespace app\modules\bipl\models;

/**
 * This is the ActiveQuery class for [[BiplCollection]].
 *
 * @see BiplCollection
 */
class BiplCollectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BiplCollection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BiplCollection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
