<?php

namespace backend\controllers;

use frontend\models\Transport;
use frontend\models\TransportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\log\Logger;

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
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Transport models.
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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::info("Transport model created successfully with ID: {$model->id}", __METHOD__);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error("Failed to create Transport model. Errors: " . json_encode($model->errors), __METHOD__);
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
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::info("Transport model with ID: {$model->id} updated successfully.", __METHOD__);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if (!$model->hasErrors()) {
                Yii::error("Failed to update Transport model with ID: {$id} due to unknown reasons.", __METHOD__);
            } else {
                Yii::error("Failed to update Transport model with ID: {$id}. Errors: " . json_encode($model->errors), __METHOD__);
            }
        }

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
            Yii::info("Transport model with ID: {$id} deleted successfully.", __METHOD__);
        } catch (\Exception $e) {
            Yii::error("Error occurred while deleting Transport model with ID: {$id}. Exception: " . $e->getMessage(), __METHOD__);
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

        Yii::error("Transport model with ID: {$id} not found.", __METHOD__);
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
