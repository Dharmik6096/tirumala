<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblBloodgroup]].
 *
 * @see TblBloodgroup
 */
class TblBloodgroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBloodgroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBloodgroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
