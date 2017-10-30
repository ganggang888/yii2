<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company;

/**
 * SearchCompany represents the model behind the search form of `app\models\Company`.
 */
class SearchCompany extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'term_id', 'postal_code', 'phone', 'tax_phone'], 'integer'],
            [['enterprise_code', 'name', 'address', 'area', 'place', 'industry', 'telephone', 'establish_day', 'credit_code', 'tax_name', 'tax_telephone', 'settlement', 'bank_name', 'bank_number', 'is_cognizance', 'is_ratepaying', 'is_close', 'remark', 'certificates','legal_person'], 'safe'],
            [['capital', 'increment', 'business', 'income', 'personal', 'manage'], 'number'],
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
        $query = Company::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => '20',
            ]
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
            'term_id' => $this->term_id,
            'establish_day' => $this->establish_day,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'capital' => $this->capital,
            'tax_phone' => $this->tax_phone,
            'increment' => $this->increment,
            'business' => $this->business,
            'income' => $this->income,
            'personal' => $this->personal,
            'manage' => $this->manage,
            'legal_person' => $this->legal_person,
        ]);

        $query->andFilterWhere(['like', 'enterprise_code', $this->enterprise_code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'industry', $this->industry])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'credit_code', $this->credit_code])
            ->andFilterWhere(['like', 'tax_name', $this->tax_name])
            ->andFilterWhere(['like', 'tax_telephone', $this->tax_telephone])
            ->andFilterWhere(['like', 'settlement', $this->settlement])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_number', $this->bank_number])
            ->andFilterWhere(['like', 'is_cognizance', $this->is_cognizance])
            ->andFilterWhere(['like', 'is_ratepaying', $this->is_ratepaying])
            ->andFilterWhere(['like', 'is_close', $this->is_close])
            ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['like','legal_person',$this->legal_person])
            ->andFilterWhere(['like', 'certificates', $this->certificates]);

        return $dataProvider;
    }
}
