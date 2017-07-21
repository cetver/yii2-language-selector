<?php

namespace cetver\LanguageSelector\tests\unit;

use cetver\LanguageSelector\items\DropDownLanguageItem;
use cetver\LanguageSelector\tests\_data\controllers\SiteController;
use Codeception\Test\Unit;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\Application;

abstract class AbstractUnitTest extends Unit
{
    /**
     * @var \cetver\LanguageSelector\tests\UnitTester
     */
    protected $tester;
    protected $languages = [
        'en' => 'English',
        'ru' => 'Russian',
    ];

    public function testInit()
    {
        $item = new DropDownLanguageItem([
            'languages' => function () {
                return $this->languages;
            },
        ]);
        $this->tester->assertSame($this->languages, $item->languages);

        $this->tester->expectException(
            new InvalidConfigException(
                'The "languages" property must be an array or callable function that returns an array'
            ),
            function () {
                new DropDownLanguageItem(['languages' => null]);
            }
        );
    }

    protected function mockWebApplication($config = [])
    {
        new Application(ArrayHelper::merge(
            [
                'id' => 'test-app',
                'basePath' => __DIR__,
                'components' => [
                    'request' => [
                        'scriptUrl' => '',
                    ],
                    'urlManager' => [
                        'enablePrettyUrl' => true,
                        'showScriptName' => false,
                    ],
                ],
            ],
            $config
        ));
        Yii::$app->controller = new SiteController('site/index', Yii::$app);
    }
}