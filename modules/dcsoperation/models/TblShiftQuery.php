<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblShift]].
 *
 * @see TblShift
 */
class TblShiftQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblShift[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblShift|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
