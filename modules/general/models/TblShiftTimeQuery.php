<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblShiftTime]].
 *
 * @see TblShiftTime
 */
class TblShiftTimeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblShiftTime[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblShiftTime|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
