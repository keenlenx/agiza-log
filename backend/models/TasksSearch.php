<?
namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Moving;

/**
 * TasksSearch represents the model behind the search form of `frontend\models\Moving`.
 */
class TasksSearch extends Moving
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'partner_id'], 'integer'],
            [['time_created', 'Customer_name', 'Customer_phone', 'Customer_email', 'from_address', 'to_address', 'Move_description', 'Moving_status', 'payment_status', 'transaction_id', 'Start_time', 'End_time', 'Stripe_code'], 'safe'],
            [['Distance', 'price'], 'number'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Moving::find();

        // Add conditions that should always apply
        // e.g. if there's any specific filter, you can apply them here
        // Example: $query->andFilterWhere(['status' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,  // Adjust the pagination if needed
            ],
            'sort' => [
                'defaultOrder' => [
                    'time_created' => SORT_DESC,  // Change sorting if necessary
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // If validation fails, return empty data provider to prevent invalid results
            return $dataProvider;
        }

        // Apply grid filtering conditions
        $query->andFilterWhere([
            'transaction_id' => $this->transaction_id,
            'Customer_name' => $this->Customer_name,
            'price' => $this->price,
            'partner_id' => $this->partner_id,
        ]);

        // Apply partial matching (LIKE) conditions for string fields
        $query->andFilterWhere(['like', 'Customer_name', $this->Customer_name])
            ->andFilterWhere(['like', 'Customer_phone', $this->Customer_phone])
            ->andFilterWhere(['like', 'Customer_email', $this->Customer_email])
            ->andFilterWhere(['like', 'from_address', $this->from_address])
            ->andFilterWhere(['like', 'to_address', $this->to_address])
            ->andFilterWhere(['like', 'Move_description', $this->Move_description])
            ->andFilterWhere(['like', 'Moving_status', $this->Moving_status])
            ->andFilterWhere(['like', 'payment_status', $this->payment_status])
            ->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['like', 'Start_time', $this->Start_time])
            ->andFilterWhere(['like', 'End_time', $this->End_time])
            ->andFilterWhere(['like', 'Stripe_code', $this->Stripe_code]);

        return $dataProvider;
    }
}
