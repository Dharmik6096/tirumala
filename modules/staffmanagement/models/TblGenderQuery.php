<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblGender]].
 *
 * @see TblGender
 */
class TblGenderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblGender[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblGender|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
