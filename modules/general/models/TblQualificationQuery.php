<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblQualification]].
 *
 * @see TblQualification
 */
class TblQualificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblQualification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblQualification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
