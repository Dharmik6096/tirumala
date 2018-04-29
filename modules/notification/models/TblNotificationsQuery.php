<?php

namespace app\modules\notification\models;

/**
 * This is the ActiveQuery class for [[TblNotifications]].
 *
 * @see TblNotifications
 */
class TblNotificationsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblNotifications[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblNotifications|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
