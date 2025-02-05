<?php

namespace frontend\controllers;

use frontend\models\Transport;
use frontend\models\TransportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\assets\PricingHelper;
use frontend\assets\DistanceHelper;
use frontend\assets\GeoHelper;
use Yii;

/**
 * TransportController implements the CRUD actions for Transport model.
 */
class TransportController extends Controller
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
                'only' => ['delete', 'index', 'update', 'transport'], // Specify actions to apply access control
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'transport'], // Allow these actions for all authenticated users
                        'roles' => ['@'], // '@' means authenticated users
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'], // Allow 'delete' only for users with 'admin' role
                        'roles' => ['admin'], // Restrict to admin role
                    ],
                    [
                        'allow' => false,
                        'actions' => ['delete'], // Explicitly deny 'delete' for all other users
                        'roles' => ['?'], // '?' means guest users
                    ],
                    [
                        'allow' => false,
                        'actions' => ['delete'], // Explicitly deny 'delete' for non-admin authenticated users
                        'roles' => ['@'], 
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->can('admin'); // Deny if user is not an admin
                        },
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

    protected function sendEmail($toEmail, $subject, $body)
    {
        try {
            // Send email using Yii's mailer component
            Yii::$app->mailer->compose()
                ->setTo($toEmail)
                ->setFrom(Yii::$app->params['adminEmail']) // Replace with the sender's email if needed
                ->setSubject($subject)
                ->setTextBody($body) // Send plain text body (can also use setHtmlBody for HTML)
                ->send();

            // Log email sent
            Yii::info('Email sent to ' . $toEmail . ' with subject: ' . $subject);
            return true;
        } catch (\Exception $e) {
            // Log the error if email failed to send
            Yii::error('Error sending email: ' . $e->getMessage());
            return false;
        }
    }
    /**
     * Lists all transport models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TransportSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transport model.
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
     * Creates a new Transport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Transport();
        $model->transaction_id = PricingHelper::gen_txnid();

        // Check if the form is submitted
        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {
                // Validate input before processing geocoding and price calculations
                if (empty($model->from_address) || empty($model->to_address)) {
                    Yii::$app->session->setFlash('error', 'Please provide both pickup and delivery addresses.');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Get the distance and price based on the source and destination addresses
                $result = $this->getDistanceAndPrice($model->from_address, $model->to_address);
                
                if ($result) {
                    $distance = $result['distance']; 
                    $price = $result['price'];  // Extract distance and price

                    // Assign the calculated values to the model
                    $model->Distance = $distance;
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
                    Yii::info('Transport record created successfully with ID: ' . $model->id);
                    Yii::$app->session->setFlash('success', 'Transport record created successfully. Transaction ID: ' . $model->transaction_id);
                     $this->sendEmail(
                    Yii::$app->params['adminEmail'], // admin email address
                    'New  Record Created', // Email subject
                    'A new  record has been created. Transaction ID: ' . $model->transaction_id // Email body
                );
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::error('Error creating Transport record: ' . print_r($model->errors, true));
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
     * Updates an existing Transport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // Step 1: Find the model
        $model = $this->findModel($id);

        // Step 2: Check if the form is submitted
        if ($this->request->isPost && $model->load($this->request->post())) {
            //If the transaction ID was not created in the first place
             if (empty($model->transaction_id)) {
                // If transaction_id is empty, generate a new one
                $model->transaction_id = PricingHelper::gen_txnid();
                Yii::info('Generated new transaction ID: ' . $model->transaction_id);
            }

            // Step 3: Get the pickup and delivery addresses
            $pickupAddress = $model->from_address;
            $deliveryAddress = $model->to_address;

            // Ensure that both pickup and delivery addresses are provided
            if (!empty($pickupAddress) && !empty($deliveryAddress)) {
                // Step 4: Use the helper method to calculate distance and price
                $result = $this->getDistanceAndPrice($pickupAddress, $deliveryAddress);

                // Step 5: Check if calculation was successful
                if ($result) {
                    $distance = $result['distance'];
                    $price = $result['price'];

                    // Step 6: Assign the calculated values to the model
                    $model->Distance = $distance;
                    $model->price = $price;
                } else {
                    // If there was an issue with the calculation, return an error message
                    Yii::$app->session->setFlash('error', 'Failed to calculate the distance and price.');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } else {
                // If either address is empty, show an error message
                Yii::$app->session->setFlash('error', 'Both pickup and delivery addresses must be provided.');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            // Step 7: Save the model with the updated values
            if ($model->save()) {
                Yii::info('Transport record updated successfully with ID: ' . $model->id);
                Yii::$app->session->setFlash('success', 'Transport record updated successfully. ID: ' . $model->id);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error('Error updating Transport record with ID ' . $model->id . ': ' . print_r($model->errors, true));
                Yii::$app->session->setFlash('error', 'Failed to update Transport record.');
            }
        }

        // Render the update view with the model
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::info('Transport record deleted successfully with ID: ' . $id);
            Yii::$app->session->setFlash('success', 'Transport record deleted successfully.');
        } catch (\Exception $e) {
            Yii::error('Error deleting Transport record with ID ' . $id . ': ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Failed to delete Transport record.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Transport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transport::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Calculates distance and price based on pickup and delivery addresses.
     * @param string $pickupAddress
     * @param string $deliveryAddress
     * @return array|null
     */
    public function getDistanceAndPrice($pickupAddress, $deliveryAddress)
    {
        // Ensure that addresses are not empty
        if (empty($pickupAddress) || empty($deliveryAddress)) {
            Yii::$app->session->setFlash('error', 'Pickup or delivery address is missing.');
            return null;
        }

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
        $price = PricingHelper::TransportPrice($distance);

        // Return both the distance and the price in an associative array
        return [
            'distance' => $distance, // The distance in kilometers
            'price' => $price // The calculated price based on the distance
        ];
    }

}
?>
