<?php

namespace app\modules\bipl\models;

/**
 * This is the ActiveQuery class for [[BiplFtpCollection]].
 *
 * @see BiplFtpCollection
 */
class BiplFtpCollectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BiplFtpCollection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BiplFtpCollection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
