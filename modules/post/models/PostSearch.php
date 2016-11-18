<?php

namespace anda\cms\modules\post\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use anda\cms\modules\post\models\Post;
use anda\cms\modules\category\models\Category;

/**
 * PostSearch represents the model behind the search form about `anda\cms\modules\post\models\Post`.
 */
class PostSearch extends Post
{
//    public $category_name;

//    public $category_root;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status', 'hits', 'version', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['title', 'slug', 'introtext', 'content', 'image', 'published_at', 'publish_up', 'publish_down', 'meta_title', 'meta_keywords', 'meta_description', 'deleted_at'
                , 'created_by', 'category_name', 'category_root', 'globalSearch'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = parent::find();

//        $query->joinWith(['category', 'createdBy.profile']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
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
            'category_id' => $this->category_id,
            'status' => $this->status,
            'hits' => $this->hits,
            'published_at' => $this->published_at,
            'publish_up' => $this->publish_up,
            'publish_down' => $this->publish_down,
            'version' => $this->version,
//            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
//            Category::tableName().'.root' => $this->category_root,
        ]);

        $query->andFilterWhere(['like', self::tableName().'.title', $this->title])
            ->andFilterWhere(['like', self::tableName().'.slug', $this->slug])
            ->andFilterWhere(['like', self::tableName().'.introtext', $this->introtext])
            ->andFilterWhere(['like', self::tableName().'.content', $this->content])
            ->andFilterWhere(['like', self::tableName().'.image', $this->image])
            ->andFilterWhere(['like', self::tableName().'.meta_title', $this->meta_title])
            ->andFilterWhere(['like', self::tableName().'.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', self::tableName().'.meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'prof.firstname', $this->created_by]);

        $query->andFilterWhere(['like', self::tableName().'.title', $this->globalSearch])
            ->orFilterWhere(['like', self::tableName().'.content', $this->globalSearch]);

        return $dataProvider;
    }
}
