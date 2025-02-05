<?php

namespace backend\controllers;

use yii;
use frontend\models\Moving;
use backend\models\TasksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TasksController implements the CRUD actions for Moving model.
 */
class TasksController extends Controller
{
    /**
     * @inheritDoc
     */

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                // VerbFilter to restrict HTTP verbs for specific actions (like POST for delete)
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],

                // AccessControl to enforce role-based access control
                 'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['delete', 'index', 'update', 'tasks'], // Specify actions to apply access control
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'update', 'tasks'], // Allow these actions for all authenticated users
                            'roles' => ['@'], // '@' means authenticated users
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete'], // Allow 'delete' only for users with 'admin' role
                            'roles' => ['admin'], // Restrict to admin role
                        ],
                    ],
                ],
            ]
        );
    }


    /**
     * Lists all Moving models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
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
     * Updates an existing Moving model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

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
        $this->findModel($id)->delete();

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
    public function actionComplete($id)
    {
        // Find the model for the task with the provided ID
        $model = $this->findModel($id);
       if ($model->Moving_status != 'Completed' && $model->Moving_status != 'Cancelled') {
        // Set a flash error message if the status is 'Completed'
        if ($model->Moving_status == 'Completed') {
            Yii::$app->session->setFlash('error', 'Task cannot be completed because it is already marked as "Completed"'.$model->Moving_status);
        }
        // Set a flash error message if the status is 'Cancelled'
        elseif ($model->Moving_status == 'Cancelled') {
            Yii::$app->session->setFlash('error', 'Task cannot be completed because it is already marked as "Cancelled".'.$model->Moving_status);
        }
        else{
        // Set the task status to 'Completed'
        $model->Moving_status = 'Completed';
        $model->End_time = (new \DateTime())->format('Y-m-d H:i:s');

        // Try to save the model
        if ($model->save()) {

            // Set a flash success message
            Yii::$app->session->setFlash('success', 'Task marked as completed successfully.');
        } else {
            // Set a flash error message if saving fails
            Yii::$app->session->setFlash('error', 'Failed to mark the task as completed. Please try again.');
        }
    }
    } else {
        
    }

        

        // Render the view after setting the flash message
        return $this->render('view', [
            'model' => $model,
        ]);
    }

}
