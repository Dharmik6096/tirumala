<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblBmcType]].
 *
 * @see TblBmcType
 */
class TblBmcTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBmcType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBmcType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
