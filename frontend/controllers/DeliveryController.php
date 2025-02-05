<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Delivery;
use frontend\models\DeliverySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\assets\GeoHelper;
use frontend\assets\PricingHelper;
use frontend\assets\DistanceHelper;

/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends Controller
{
    /**
     * @inheritDoc
     */
public function behaviors()
{
    return array_merge(
        parent::behaviors(),
        [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete', 'index', 'update', 'delivery'], // Specify actions to apply access control
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'delivery'], // Allow these actions for all authenticated users
                        'roles' => ['@'], // '@' means authenticated users
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'], // Allow 'delete' only for users with 'admin' role
                        'roles' => ['admin'], // Restrict to admin role
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'], // Require POST for delete actions
                ],
            ],
        ]
    );
}


    /**
     * Lists all Delivery models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DeliverySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }

    /**
     * Displays a single Delivery model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Method to calculate price based on pickup and delivery coordinates.
     * @param string $pickupAddress
     * @param string $deliveryAddress
     * @return float|null
     */
    public function getDistanceAndPrice($pickupAddress, $deliveryAddress)
    {
        // Geocode the pickup address to get its latitude and longitude
        $pickupCoords = GeoHelper::geocodePickupAddress($pickupAddress);
        
        // Geocode the delivery address to get its latitude and longitude
        $deliveryCoords = GeoHelper::geocodeDeliveryAddress($deliveryAddress);

        // Check if geocoding was unsuccessful for the pickup address
        if (!$pickupCoords) {
            Yii::$app->session->setFlash('error', 'Failed to geocode pickup address: ' . $pickupAddress);
            return null; // Return null if geocoding the pickup address fails
        }

        // Check if geocoding was unsuccessful for the delivery address
        if (!$deliveryCoords) {
            Yii::$app->session->setFlash('error', 'Failed to geocode delivery address: ' . $deliveryAddress);
            return null; // Return null if geocoding the delivery address fails
        }

        // Calculate the distance between the pickup and delivery locations using latitude and longitude
        $distance = DistanceHelper::calculateDistance(
            $pickupCoords['lat'], $pickupCoords['lng'],
            $deliveryCoords['lat'], $deliveryCoords['lng'],
            'km' // Return the distance in kilometers
        );

        // Calculate the price based on the calculated distance
        $price = PricingHelper::calculatePrice($distance);

        // Return both the distance and the price in an associative array
        return [
            'distance' => $distance, // The distance in kilometers
            'price' => $price // The calculated price based on the distance
        ];
    }

    /**
     * Creates a new Delivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Delivery();
        $model->transaction_id = PricingHelper::gen_txnid();

        // Check if the form is submitted
        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                // Get the distance and price based on the source and destination addresses
                $result = $this->getDistanceAndPrice($model->source_address, $model->destination_address);  // Get the result
                
                if ($result) {
                    $distance = $result['distance']; 
                    $price = $result['price'];  // Extract distance and price

                    // Assign the calculated values to the model
                    $model->distance = $distance;
                    $model->price = $price;
                } else {
                    // If geocoding failed, do not save and display an error message
                    Yii::$app->session->setFlash('error', 'There was an error geocoding the addresses. Delivery cannot be created.');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Validate the model
                if (!$model->validate()) {
                    // Show validation errors before proceeding with save
                    Yii::$app->session->setFlash('error', 'Validation failed. Please check the input fields.' . print_r($model->errors, true));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Save the model if validation passes
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to create delivery. Please check your input.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Delivery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // Find the model based on the ID
        $model = $this->findModel($id);

        // Check if the request is a POST and the form data is loaded
        if ($this->request->isPost && $model->load($this->request->post())) {
            // Calculate price before saving
            $result = $this->getDistanceAndPrice($model->source_address, $model->destination_address);  // Get the result
            $distance = $result['distance']; 
            $price = $result['price'];  // Extract distance and price

            // Only update the price if geocoding was successful
            if ($price !== null) {
                $model->price = $price;
                $model->distance = $distance;
            } else {
                Yii::$app->session->setFlash('error', 'There was an error geocoding the addresses. Update failed.');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            // Validate the model before saving
            if (!$model->validate()) {
                // Show validation errors
                Yii::$app->session->setFlash('error', 'Validation failed. Please check the input fields.' . print_r($model->errors, true));
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            // Save the model with the updated price
            if ($model->save()) {
                // Set a success flash message with the updated price
                Yii::$app->session->setFlash('success', 'Updated successfully. New price: ' . $model->price);

                // Redirect to the view page with the model ID
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // Handle failure in saving, if any
                Yii::$app->session->setFlash('error', 'Failed to update the delivery.');
            }
        }

        // Render the update view with the model
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Delivery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Delivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Delivery::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Generates a transaction ID.
     * @return string the generated transaction ID
     */
 
}
