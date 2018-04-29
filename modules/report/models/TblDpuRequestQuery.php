<?php

namespace app\modules\report\models;

/**
 * This is the ActiveQuery class for [[TblDpuRequest]].
 *
 * @see TblDpuRequest
 */
class TblDpuRequestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDpuRequest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDpuRequest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
