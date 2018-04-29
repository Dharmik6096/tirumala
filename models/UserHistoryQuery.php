<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserHistory]].
 *
 * @see UserHistory
 */
class UserHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
