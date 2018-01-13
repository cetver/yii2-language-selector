<?php

namespace cetver\LanguageSelector\items;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class DropDownLanguageItem is used for widgets, that implements the drop-down list functionality.
 *
 * For example:
 *
 * ```php
 * // Bootstrap Nav
 * Yii::$app->language = 'en';
 * $languageItem = new cetver\LanguageSelector\items\DropDownLanguageItem([
 *     'languages' => [
 *         'en' => '<span class="flag-icon flag-icon-us"></span> English',
 *         'ru' => '<span class="flag-icon flag-icon-ru"></span> Russian',
 *         'de' => '<span class="flag-icon flag-icon-de"></span> Deutsch',
 *     ],
 *     'options' => ['encode' => false],
 * ]);
 * \yii\bootstrap\NavBar::begin([
 *     'brandLabel' => 'My Company',
 *     'brandUrl' => Yii::$app->homeUrl,
 * ]);
 * echo \yii\bootstrap\Nav::widget([
 *     'options' => ['class' => 'navbar-nav navbar-right'],
 *     'items' => [
 *         ['label' => 'Home', 'url' => ['/site/index']],
 *         ['label' => 'About', 'url' => ['/site/about']],
 *         $languageItem->toArray()
 *     ]
 * ]);
 * \yii\bootstrap\NavBar::end();
 *
 * // Bootstrap drop-down button
 * Yii::$app->language = 'en';
 * $languageItem = new cetver\LanguageSelector\items\DropDownLanguageItem([
 *     'languages' => [
 *         'en' => '<span class="flag-icon flag-icon-us"></span> English',
 *         'ru' => '<span class="flag-icon flag-icon-ru"></span> Russian',
 *         'de' => '<span class="flag-icon flag-icon-de"></span> Deutsch',
 *     ],
 *     'options' => ['encode' => false],
 * ]);
 * $languageItem = $languageItem->toArray();
 * $languageDropdownItems = \yii\helpers\ArrayHelper::remove($languageItem, 'items');
 * echo \yii\bootstrap\ButtonDropdown::widget([
 *     'label' => $languageItem['label'],
 *     'encodeLabel' => false,
 *     'options' => ['class' => 'btn-default'],
 *     'dropdown' => [
 *         'items' => $languageDropdownItems
 *     ]
 * ]);
 * ```
 *
 * @package cetver\LanguageSelector\items
 * @property array $options the drop-down widget item options, excluding "label" and "url".
 * @see \yii\bootstrap\Nav::$items
 * @see \yii\bootstrap\Dropdown::$items
 */
class DropDownLanguageItem extends AbstractLanguageItem
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $languages = $this->languages;
        $topItemLabel = ArrayHelper::remove($languages, Yii::$app->language);
        if ($topItemLabel === null) {
            throw new InvalidConfigException(sprintf(
                'The "%s" language does not exists in "%s::$languages"',
                Yii::$app->language,
                self::className()
            ));
        }
        $item = ArrayHelper::merge($this->options, [
            'label' => $topItemLabel,
            'url' => ['#'],
        ]);
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        foreach ($languages as $code => $name) {
            $queryParams[$this->queryParam] = $code;
            $route = array_merge([''], $queryParams);
            $item['items'][] = ArrayHelper::merge($this->options, [
                'label' => $name,
                'url' => Url::toRoute($route),
            ]);
        }

        return $item;
    }
}
