<?php

namespace anda\cms\modules\post\models;

use Yii;
use yii\data\ActiveDataProvider;
use anda\cms\modules\post\models\PostSearch;
use anda\cms\modules\category\models\Category;

/**
 * PostSearch represents the model behind the search form about `anda\cms\modules\post\models\Post`.
 */
class PostTrashSearch extends PostSearch
{

    public static function find()
    {
        return parent::find()->where(['!=', 'deleted_at', !null]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $query->joinWith(['category', 'createdBy.profile']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        $da

        $dataProvider->sort->attributes['created_by'] = [
            'asc' => ['created_by' => SORT_ASC],
            'desc' => ['created_by' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName().'.id' => $this->id,
            self::tableName().'.category_id' => $this->category_id,
            self::tableName().'.status' => $this->status,
            self::tableName().'.hits' => $this->hits,
            self::tableName().'.published_at' => $this->published_at,
            self::tableName().'.publish_up' => $this->publish_up,
            self::tableName().'.publish_down' => $this->publish_down,
            self::tableName().'.version' => $this->version,
//            self::tableName().'.created_by' => $this->created_by,
            self::tableName().'.created_at' => $this->created_at,
            self::tableName().'.updated_by' => $this->updated_by,
            self::tableName().'.updated_at' => $this->updated_at,
            self::tableName().'.deleted_at' => $this->deleted_at,
            Category::tableName().'.root' => $this->category_root,
        ]);

        $userProfileClass = Yii::$app->user->identity->profile->className();
        $query->andFilterWhere(['like', self::tableName().'.title', $this->title])
            ->andFilterWhere(['like', self::tableName().'.slug', $this->slug])
            ->andFilterWhere(['like', self::tableName().'.introtext', $this->introtext])
            ->andFilterWhere(['like', self::tableName().'.content', $this->content])
            ->andFilterWhere(['like', self::tableName().'.image', $this->image])
            ->andFilterWhere(['like', self::tableName().'.meta_title', $this->meta_title])
            ->andFilterWhere(['like', self::tableName().'.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', self::tableName().'.meta_description', $this->meta_description])
            ->andFilterWhere(['like', $userProfileClass::tableName().'.firstname', $this->created_by]);

        return $dataProvider;
    }
}
