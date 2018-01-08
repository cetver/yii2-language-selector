<?php

namespace cetver\LanguageSelector\items;

use yii\base\InvalidConfigException;
use yii\base\BaseObject;

/**
 * Class AbstractLanguageItem is a simple implementation that other classes can inherit from.
 *
 * @package cetver\LanguageSelector\items
 */
abstract class AbstractLanguageItem extends BaseObject
{
    /**
     * @var array|callable the list of available languages.
     */
    public $languages = [];
    /**
     * @var string the query parameter name that contains a language.
     * @see \yii\web\Request::getQueryParams()
     */
    public $queryParam = 'language';
    /**
     * @var array options for each array element, returning by the "toArray" method, for more info, take a look at the
     * implemented classes.
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (is_callable($this->languages)) {
            $this->languages = call_user_func($this->languages);
        }
        if (!is_array($this->languages)) {
            throw new InvalidConfigException(
                'The "languages" property must be an array or callable function that returns an array'
            );
        }
    }

    /**
     * Returns elements, containing URLs, with a new language.
     *
     * @return array
     */
    public function toArray()
    {
        return [];
    }
}