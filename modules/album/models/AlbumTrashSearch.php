<?php

namespace anda\cms\modules\album\models;

use Yii;
use yii\data\ActiveDataProvider;
use anda\cms\modules\album\models\AlbumSearch;

/**
 * PostSearch represents the model behind the search form about `anda\cms\modules\post\models\Post`.
 */
class AlbumTrashSearch extends AlbumSearch
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
            'id' => $this->id,
            'category_id' => $this->category_id,
            self::tableName().'.status' => $this->status,
            'hits' => $this->hits,
            'published_at' => $this->published_at,
//            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $userProfileClass = Yii::$app->user->identity->profile->className();
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'introtext', $this->introtext])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', $userProfileClass::tableName().'.firstname', $this->created_by]);

        return $dataProvider;
    }
}
