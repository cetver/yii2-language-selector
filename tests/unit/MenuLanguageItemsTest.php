<?php

namespace cetver\LanguageSelector\tests\unit;

use cetver\LanguageSelector\items\MenuLanguageItems;
use Yii;

class MenuLanguageItemsTest extends AbstractUnitTest
{
    public function testToArray()
    {
        $this->mockWebApplication();
        Yii::$app->language = 'en';
        $item = new MenuLanguageItems(['languages' => $this->languages]);
        $this->tester->assertSame(
            [
                [
                    'label' => 'English',
                    'url' => '/site/index?language=en',
                    'active' => true,
                ],
                [
                    'label' => 'Russian',
                    'url' => '/site/index?language=ru',
                    'active' => false,
                ],
            ],
            $item->toArray()
        );

        Yii::$app->language = 'ru';
        $item = new MenuLanguageItems(['languages' => $this->languages]);
        $this->tester->assertSame(
            [
                [
                    'label' => 'English',
                    'url' => '/site/index?language=en',
                    'active' => false,
                ],
                [
                    'label' => 'Russian',
                    'url' => '/site/index?language=ru',
                    'active' => true,
                ],
            ],
            $item->toArray()
        );

        Yii::$app->language = 'en';
        $item = new MenuLanguageItems([
            'languages' => $this->languages,
            'options' => [
                'q' => 'q',
                'w' => 'w',
                'label' => 'label',
                'url' => 'url',
                'active' => 'active',
            ],
            'queryParam' => 'lang',
        ]);
        $this->tester->assertSame(
            [
                [
                    'q' => 'q',
                    'w' => 'w',
                    'label' => 'English',
                    'url' => '/site/index?lang=en',
                    'active' => true,
                ],
                [
                    'q' => 'q',
                    'w' => 'w',
                    'label' => 'Russian',
                    'url' => '/site/index?lang=ru',
                    'active' => false,
                ],
            ],
            $item->toArray()
        );

        $this->mockWebApplication([
            'components' => [
                'urlManager' => [
                    'rules' => [
                        [
                            'pattern' => 'site/<index:\w+>',
                            'route' => 'site/index',
                            'suffix' => '.html',
                        ],
                    ],
                ],
                'request' => [
                    'queryParams' => [
                        'index' => 'index',
                        'param' => 'param',
                        '#' => 'anchor',
                    ],
                ],
            ],
        ]);
        Yii::$app->language = 'en';
        $item = new MenuLanguageItems(['languages' => $this->languages]);
        $this->tester->assertSame(
            [
                [
                    'label' => 'English',
                    'url' => '/site/index.html?param=param&language=en#anchor',
                    'active' => true,
                ],
                [
                    'label' => 'Russian',
                    'url' => '/site/index.html?param=param&language=ru#anchor',
                    'active' => false,
                ],
            ],
            $item->toArray()
        );
    }
}
