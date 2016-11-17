<?php

namespace anda\cms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use anda\cms\models\Module;

/**
 * ModuleSearch represents the model behind the search form about `anda\cms\models\Module`.
 */
class ModuleSearch extends Module
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_num', 'status'], 'integer'],
            [['name', 'title', 'class', 'icon', 'settings'], 'safe'],
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
        $query = Module::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_num' => $this->order_num,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'settings', $this->settings]);

        return $dataProvider;
    }
}
