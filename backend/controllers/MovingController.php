<?php

namespace backend\controllers;

use frontend\models\Moving;
use frontend\models\MovingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\log\Logger;

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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::info("Moving model created successfully with ID: {$model->id}", __METHOD__);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error("Failed to create Moving model. Errors: " . json_encode($model->errors), __METHOD__);
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
            Yii::info("Moving model with ID: {$model->id} updated successfully.", __METHOD__);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if (!$model->hasErrors()) {
                Yii::error("Failed to update Moving model with ID: {$id} due to unknown reasons.", __METHOD__);
            } else {
                Yii::error("Failed to update Moving model with ID: {$id}. Errors: " . json_encode($model->errors), __METHOD__);
            }
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
        try {
            $this->findModel($id)->delete();
            Yii::info("Moving model with ID: {$id} deleted successfully.", __METHOD__);
        } catch (\Exception $e) {
            Yii::error("Error occurred while deleting Moving model with ID: {$id}. Exception: " . $e->getMessage(), __METHOD__);
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

        Yii::error("Moving model with ID: {$id} not found.", __METHOD__);
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
