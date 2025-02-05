<?php

namespace frontend\helpers;

use yii\helpers\Html;

class FormHelper
{
    /**
     * Render error message in a styled way.
     *
     * @param \yii\base\Model $model The model instance.
     * @param string $attribute The attribute to display the error for.
     * @return string The HTML for the error message.
     */
    public static function renderError($model, $attribute)
    {
        if ($model->hasErrors($attribute)) {
            $error = Html::encode($model->getFirstError($attribute));
            return Html::tag('div', $error, ['class' => 'error']);
        }
        return '';
    }
}
