<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Checklist;

/**
 * ChecklistSearch represents the model behind the search form of `backend\models\Checklist`.
 */
class ChecklistSearch extends Checklist
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['report_task', 'frequency', 'deadline', 'submitted', 'submission_date', 'conditions'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Checklist::find();

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
            'deadline' => $this->deadline,
            'submission_date' => $this->submission_date,
        ]);

        $query->andFilterWhere(['like', 'report_task', $this->report_task])
            ->andFilterWhere(['like', 'frequency', $this->frequency])
            ->andFilterWhere(['like', 'submitted', $this->submitted])
            ->andFilterWhere(['like', 'conditions', $this->conditions]);

        return $dataProvider;
    }
}
