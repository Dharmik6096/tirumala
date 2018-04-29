<?php

namespace app\modules\bipl\models;

/**
 * This is the ActiveQuery class for [[BiplCleaning]].
 *
 * @see BiplCleaning
 */
class BiplCleaningQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BiplCleaning[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BiplCleaning|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
