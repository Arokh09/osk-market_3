<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skills".
 *
 * @property int $id
 * @property string $name
 *
 * @property UserSkills[] $userSkills
 * @property Users[] $users
 */
class Skills extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[UserSkills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSkills()
    {
        return $this->hasMany(UserSkills::className(), ['skill_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['id' => 'user_id'])->viaTable('user_skills', ['skill_id' => 'id']);
    }
}
