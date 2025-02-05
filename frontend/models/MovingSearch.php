<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Moving;

/**
 * MovingSearch represents the model behind the search form of `frontend\models\Moving`.
 */
class MovingSearch extends Moving
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'partner_id'], 'integer'],
            [['time_created', 'Customer_name', 'Customer_phone', 'Customer_email', 'from_address', 'to_address', 'Move_description', 'Moving_status', 'payment_status', 'price', 'transaction_id', 'Start_time', 'End_time'], 'safe'],
            [['Distance'], 'number'],
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
        $query = Moving::find();

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
            'time_created' => $this->time_created,
            'Distance' => $this->Distance,
            'partner_id' => $this->partner_id,
        ]);

        $query->andFilterWhere(['like', 'Customer_name', $this->Customer_name])
            ->andFilterWhere(['like', 'Customer_phone', $this->Customer_phone])
            ->andFilterWhere(['like', 'Customer_email', $this->Customer_email])
            ->andFilterWhere(['like', 'from_address', $this->from_address])
            ->andFilterWhere(['like', 'to_address', $this->to_address])
            ->andFilterWhere(['like', 'Move_description', $this->Move_description])
            ->andFilterWhere(['like', 'Moving_status', $this->Moving_status])
            ->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['like', 'Start_time', $this->Start_time])
            ->andFilterWhere(['like', 'End_time', $this->End_time]);

        return $dataProvider;
    }
}
