<?php

namespace app\modules\changelog\models;

/**
 * This is the ActiveQuery class for [[TblChangeLog]].
 *
 * @see TblChangeLog
 */
class TblChangeLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblChangeLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblChangeLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
