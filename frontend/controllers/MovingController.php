<?php

namespace frontend\controllers;

use frontend\models\Moving;
use frontend\models\MovingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\assets\PricingHelper;
use frontend\assets\DistanceHelper;
use frontend\assets\GeoHelper;
use Yii;

/**
 * MovingController implements the CRUD actions for Moving model.
 */
class MovingController extends Controller
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
                'only' => ['delete', 'index', 'update', 'moving'], // Specify actions to apply access control
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'moving'], // Allow these actions for all authenticated users
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
     * Lists all Moving models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MovingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Moving model.
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
     * Creates a new Moving model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
public function actionCreate()
{
    $model = new Moving();
    $model->transaction_id = PricingHelper::gen_txnid();

    // Get current user data
    $user = Yii::$app->user->identity;

    if ($user) {
        $model->Customer_email = $user->email;
        $model->Customer_phone = $user->phone_no;
        $model->from_address = $user->usr_address;
    }

// Render the view with the prefilled model
return $this->render('create', [
    'model' => $model,
]);
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
                $model->deposit = 0.3 * $price;
                $model->balance = $price - $model->deposit;
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
                // Log the creation of the moving record
                Yii::info('Moving record created successfully with ID: ' . $model->id);
                 // Notify the admin about the new moving record creation
                $this->sendEmail(
                    Yii::$app->params['adminEmail'], // admin email address
                    'New Moving Record Created', // Email subject
                    'A new moving record has been created. Transaction ID: ' . $model->transaction_id // Email body
                );

                // Set success message and redirect to the view page
                Yii::$app->session->setFlash('success', 'Moving record created successfully. Transaction ID: ' . $model->transaction_id);
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                // Log errors if saving fails
                Yii::error('Error creating Moving record: ' . print_r($model->errors, true));
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
     * Updates an existing Moving model.
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
                $model->price = PricingHelper::MovingPrice($distance,$model->assistance,$model->Elevator,$model->Floor_no);
                $model->balance = $model->price-$model->deposit;

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
            Yii::info('Moving record updated successfully with ID: ' . $model->id);
            Yii::$app->session->setFlash('success', 'Moving record updated successfully. ID: ' . $model->id);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::error('Error updating Moving record with ID ' . $model->id . ': ' . print_r($model->errors, true));
            Yii::$app->session->setFlash('error', 'Failed to update Moving record.');
        }
    }

    // Render the update view with the model
    return $this->render('update', [
        'model' => $model,
    ]);
}


    /**
     * Deletes an existing Moving model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::info('Moving record deleted successfully with ID: ' . $id);
            Yii::$app->session->setFlash('success', 'Moving record deleted successfully.');
        } catch (\Exception $e) {
            Yii::error('Error deleting Moving record with ID ' . $id . ': ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Failed to delete Moving record.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Moving model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Moving the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Moving::findOne(['id' => $id])) !== null) {
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
        $price = PricingHelper::MovingPrice($distance,'','','');

        // Return both the distance and the price in an associative array
        return [
            'distance' => $distance, // The distance in kilometers
            'price' => $price // The calculated price based on the distance

        ];
    }

    /**
 * Simple reusable mailer function to send emails.
 * 
 * @param string $toEmail The recipient email address
 * @param string $subject The email subject
 * @param string $body The email body content
 * @return bool Whether the email was sent successfully
 */


}
