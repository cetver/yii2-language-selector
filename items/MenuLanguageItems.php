<?php

namespace cetver\LanguageSelector\items;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class MenuLanguageItems is used for widgets, that implements the menu functionality.
 *
 * For example:
 *
 * ```php
 * $languageItems = new cetver\LanguageSelector\items\MenuLanguageItems([
 *     'languages' => [
 *         'en' => '<span class="flag-icon flag-icon-us"></span> English',
 *         'ru' => '<span class="flag-icon flag-icon-ru"></span> Russian',
 *         'de' => '<span class="flag-icon flag-icon-de"></span> Deutsch',
 *     ],
 *     'options' => ['encode' => false],
 * ]);
 * echo \yii\widgets\Menu::widget([
 *     'options' => ['class' => 'list-inline'],
 *     'items' => $languageItems->toArray(),
 * ]);
 * ```
 *
 * @package cetver\LanguageSelector\items
 * @property array $options the menu widget item options, excluding "label", "url" and "active"
 * @see \yii\widgets\Menu::$items
 */
class MenuLanguageItems extends AbstractLanguageItem
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $items = [];
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        foreach ($this->languages as $code => $name) {
            $queryParams[$this->queryParam] = $code;
            $route = array_merge([''], $queryParams);
            $items[] = ArrayHelper::merge($this->options, [
                'label' => $name,
                'url' => Url::toRoute($route),
                'active' => ($code === Yii::$app->language)
            ]);
        }
        return $items;
    }
}
