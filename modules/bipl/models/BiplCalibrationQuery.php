<?php

namespace app\modules\bipl\models;

/**
 * This is the ActiveQuery class for [[BiplCalibration]].
 *
 * @see BiplCalibration
 */
class BiplCalibrationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BiplCalibration[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BiplCalibration|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
