<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TblCleaningDpu]].
 *
 * @see TblCleaningDpu
 */
class TblCleaningDpuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblCleaningDpu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblCleaningDpu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
