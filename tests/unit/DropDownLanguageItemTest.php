<?php

namespace cetver\LanguageSelector\tests\unit;

use cetver\LanguageSelector\items\DropDownLanguageItem;
use Yii;
use yii\base\InvalidConfigException;

class DropDownLanguageItemTest extends AbstractUnitTest
{
    public function testToArray()
    {
        $language = 'invalid';
        $this->tester->expectException(
            new InvalidConfigException(sprintf(
                'The "%s" language does not exists in "%s::$languages"',
                $language,
                DropDownLanguageItem::className()
            )),
            function () use ($language) {
                $this->mockWebApplication();
                Yii::$app->language = $language;
                $item = new DropDownLanguageItem();
                $item->toArray();
            }
        );

        $this->mockWebApplication();
        Yii::$app->language = 'en';
        $item = new DropDownLanguageItem(['languages' => $this->languages]);
        $this->tester->assertSame(
            [
                'label' => 'English',
                'url' => ['#'],
                'items' => [
                    [
                        'label' => 'Russian',
                        'url' => '/site/index?language=ru',
                    ],
                ],
            ],
            $item->toArray()
        );
        $this->tester->assertSame(
            [
                'label' => 'English',
                'url' => ['#'],
                'items' => [
                    [
                        'label' => 'Russian',
                        'url' => '/site/index?language=ru',
                    ],
                ],
            ],
            $item->toArray()
        );

        Yii::$app->language = 'ru';
        $item = new DropDownLanguageItem(['languages' => $this->languages]);
        $this->tester->assertSame(
            [
                'label' => 'Russian',
                'url' => ['#'],
                'items' => [
                    [
                        'label' => 'English',
                        'url' => '/site/index?language=en',
                    ],
                ],
            ],
            $item->toArray()
        );

        Yii::$app->language = 'en';
        $item = new DropDownLanguageItem([
            'languages' => $this->languages,
            'options' => [
                'q' => 'q',
                'w' => 'w',
                'label' => 'label',
                'url' => 'url',
            ],
            'queryParam' => 'lang',
        ]);
        $this->tester->assertSame(
            [
                'q' => 'q',
                'w' => 'w',
                'label' => 'English',
                'url' => ['#'],
                'items' => [
                    [
                        'q' => 'q',
                        'w' => 'w',
                        'label' => 'Russian',
                        'url' => '/site/index?lang=ru',
                    ],
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
                        '#' => 'anchor'
                    ],
                ],
            ],
        ]);
        Yii::$app->language = 'en';
        $item = new DropDownLanguageItem(['languages' => $this->languages]);
        $this->tester->assertSame(
            [
                'label' => 'English',
                'url' => ['#'],
                'items' => [
                    [
                        'label' => 'Russian',
                        'url' => '/site/index.html?param=param&language=ru#anchor',
                    ],
                ],
            ],
            $item->toArray()
        );
    }
}