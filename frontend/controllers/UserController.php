<?php

namespace frontend\controllers;

use yii;
use frontend\models\User;
use frontend\models\UserSearch;
use frontend\models\Delivery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller{
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
                'only' => ['delete', 'index', 'update', 'user','view'], // Specify actions to apply access control
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'user','view'], // Allow these actions for all authenticated users
                        'roles' => ['@'], // '@' means authenticated users
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'], // Allow 'delete' only for users with 'admin' role
                        'roles' => ['admin'], // Restrict to admin role
                    ],
                    [
                        'allow' => false,
                        'actions' => ['delete','index'], // Explicitly deny 'delete' for all other users
                        'roles' => ['?','@'], // '?' means guest users
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
    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
public function actionUpdate($id)
{
    // Find the model by ID or throw a 404 error if not found
    $model = $this->findModel($id);

    if ($this->request->isPost) {
        // Load POST data into the model
        if ($model->load($this->request->post())) {
            if ($model->validate()) { // Validate before saving
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'User updated successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save the user. Please try again.');
                    Yii::error("User update failed for ID: {$id}. Errors: " . json_encode($model->errors), __METHOD__);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validation failed. Please check the input fields.');
                Yii::error("Validation failed for user ID: {$id}. Errors: " . json_encode($model->errors), __METHOD__);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Failed to load submitted data.');
            Yii::error("Failed to load data into user model for ID: {$id}", __METHOD__);
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}

    public function actionUpdateProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $model = Yii::$app->user->identity; // Get the logged-in user

        // Load only provided fields
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post($model->formName()); // Get form data
            
            foreach ($postData as $attribute => $value) {
                if ($model->hasAttribute($attribute)) {
                    $model->$attribute = $value; // Assign only existing attributes
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Profile updated successfully.');
                return $this->redirect(['update-profile']); // Redirect after successful update
            }
        }

        return $this->render('update-profile', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
       public function getDelivery()
    {
        return $this->hasMany(Order::class, ['phone_no' => 'sender_phone']);
    }

    public function actionUserDeliveries($phone_no)
    {
        $user = User::findOne(['phone_no' => $phone_no]);

        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $deliveries = $user->delivery;

        return $this->render('user-deliveries', [
            'user' => $user,
            'deliveries' => $deliveries,
        ]);
}

}