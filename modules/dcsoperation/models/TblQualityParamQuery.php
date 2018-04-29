<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblQualityParam]].
 *
 * @see TblQualityParam
 */
class TblQualityParamQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblQualityParam[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblQualityParam|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
