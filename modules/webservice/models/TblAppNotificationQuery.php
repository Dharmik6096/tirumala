<?php

namespace app\modules\webservice\models;

/**
 * This is the ActiveQuery class for [[TblAppNotification]].
 *
 * @see TblAppNotification
 */
class TblAppNotificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblAppNotification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblAppNotification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
