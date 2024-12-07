<?php

namespace App\Helpers;

use App\Models\Field;
use App\Models\Widget;
use Faker\Provider\Lorem;

class WidgetHelper
{
    public static function fillDefaultFieldsValue(Widget $widget):void
    {
        switch ($widget->key){
            case 'background-image-h2-effect':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => 'yes'],
                    ['type' => 'text', 'value' => '0'],
                    ['type' => 'text', 'value' => 'rgba(0, 0, 0, 0.7)'],
                    ['type' => 'text', 'value' => 'rgba(0, 0, 0, 0.7)'],
                    ['type' => 'file', 'value' => self::largeImage()],
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => '15rem'],
                    ['type' => 'text', 'value' => '30rem'],
                    ['type' => 'text', 'value' => 'left'],
                    ['type' => 'text', 'value' => '12'],
                ]);
                break;

            case 'iphone-15-pro':
                self::createFields($widget, [
                    ['type' => 'file', 'value' => '/themelight-bootstrap-main/img/blog-details-img2.jpg'],
                    ['type' => 'text', 'value' => '#101010'],
                    ['type' => 'text', 'value' => 'left'],
                    ['type' => 'text', 'value' => 'Get the full story.'],
                    ['type' => 'text', 'value' => 'Titanium is one of the strongest light metals relative to its weight. This makes these Pro models our lightest ever . You can easily feel the difference when you pick one of them up.'],
                    ['type' => 'text', 'value' => '0.2'],
                ]);
                break;

            case 'page-top-header':
                self::createFields($widget, [
                    ['type' => 'file', 'value' => self::largeImage()],
                    ['type' => 'text', 'value' => 'Clean and Flexible Business Template'],
                    ['type' => 'text', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'],
                ]);
                break;

            case 'team':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => '<h2>CREATIVE TEAM</h2><p>Now that we\'ve defined the creative team. let\'s take a closer <br> look at a team\'s individual positions and roles. </p>'], // top paragraph
                    ['type' => 'text', 'value' => '#101010'],
                    ['type' => 'text', 'value' => 'rgb(245, 245, 247)'],
                    // person 1:
                    ['type' => 'text', 'value' => 'Karsten Fjordside Poulsen'],
                    ['type' => 'text', 'value' => 'Product Designer'],
                    ['type' => 'text', 'value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempo</p>'],
                    ['type' => 'file', 'value' => '/themelight-bootstrap-main/img/team-img1.jpg'],
                    // person 2:
                    ['type' => 'text', 'value' => 'Steffen Ulsø Knudsen'],
                    ['type' => 'text', 'value' => 'Product Designer'],
                    ['type' => 'text', 'value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempo</p>'],
                    ['type' => 'file', 'value' => '/themelight-bootstrap-main/img/team-img2.jpg'],
                    // person 3:
                    ['type' => 'text', 'value' => 'Mikkel Mirsbach Rasmussen'],
                    ['type' => 'text', 'value' => 'Product Designer'],
                    ['type' => 'text', 'value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempo</p>'],
                    ['type' => 'file', 'value' => '/themelight-bootstrap-main/img/team-img3.jpg'],
                    // person 4:
                    ['type' => 'text', 'value' => 'Kasper Sonne Jensen'],
                    ['type' => 'text', 'value' => 'Product Designer'],
                    ['type' => 'text', 'value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempo</p>'],
                    ['type' => 'file', 'value' => '/themelight-bootstrap-main/img/team-img2.jpg'],
                ]);
                break;

            case 'text-one-column':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => self::longText()],
                ]);
                break;

            case 'text-two-column':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => self::longText()],
                ]);
                break;

            case 'text-three-column':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => self::longText()],
                ]);
                break;

            case 'text-free-style-one-column':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => self::longText()],
                ]);
                break;
        
            case 'text-free-style-two-column':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => self::longText()],
                ]);
                break;
        
            case 'text-free-style-three-column':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => self::longText()],
                    ['type' => 'text', 'value' => self::longText()],
                ]);
                break;
        
            case 'image-one-column':
                self::createFields($widget, [
                    ['type' => 'file', 'value' => self::largeImage()],
                ]);
                break;
        
            case 'image-two-column':
                self::createFields($widget, [
                    ['type' => 'file', 'value' => self::mediumImage()],
                    ['type' => 'file', 'value' => self::mediumImage()],
                ]);
                break;
        
            case 'image-three-column':
                self::createFields($widget, [
                    ['type' => 'file', 'value' => self::smallImage()],
                    ['type' => 'file', 'value' => self::smallImage()],
                    ['type' => 'file', 'value' => self::smallImage()],
                ]);
                break;
        
            case 'space':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => '100'],
                    ['type' => 'text', 'value' => '#FFFFFF'],
                ]);
                break;
        
            case 'block-starts':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => '12'],
                    ['type' => 'text', 'value' => '#f6f6f6'],
                    ['type' => 'text', 'value' => '0'],
                    ['type' => 'text', 'value' => '0'],
                    ['type' => 'file', 'value' => ''],
                ]);
                break;
        
            case 'block-ends':
                // No input for block-ends
                break;

            case 'code':
                self::createFields($widget, [
                    ['type' => 'text', 'value' => <<<EOD
                    <section id="counter">
                        <div class="">
                            <div class="row">
                                <div class="title">
                                    <h2>HERE IS YOUR CODE WIDGET</h2>
                                    <p>Dantes remained confused and silent by this explanation of the <br> thoughts which had
                                        unconsciously</p>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="block wow fadeInRight" data-wow-delay=".3s">
                                        <i class="ion-code"></i>
                                        <p class="count-text">
                                            <span class="counter-digit">136800 </span> k
                                        </p>
                                        <p>Lines Coded</p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="block wow fadeInRight" data-wow-delay=".5s">
                                        <i class="ion-compass"></i>
                                        <p class="count-text">
                                            <span class="counter-digit">7800 </span> +
                                        </p>
                                        <p>Lines Coded</p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="block wow fadeInRight" data-wow-delay=".7s">
                                        <i class="ion-compose"></i>
                                        <p class="count-text">
                                            <span class="counter-digit">399</span>
                                        </p>
                                        <p>Lines Coded</p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="block wow fadeInRight" data-wow-delay=".9s">
                                        <i class="ion-image"></i>
                                        <p class="count-text">
                                            <span class="counter-digit">9995</span>
                                        </p>
                                        <p>Lines Coded</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    EOD],
                ]);
                break;
                
            default:
                self::createFields($widget, [
                    ['type' => 'text', 'value' => 'In order to change the default text, please add the default text in WidgetHelper.php'],
                ]);
                break;

        }
    }

    /**
     * $allFields=
     * [
     *     [
     *          'type' => 'text', 
     *          'value' => self::longText()
     *     ],
     *     [
     *          'type' => 'text', 
     *          'value' => self::longText()
     *     ]
     * ]
     */
    private static function createFields(Widget $widget, array $allFields)
    {
        $allResultArray = [];
        $i = 0;
        foreach($allFields as $fieldArray){
            // $type = $fieldArray['type'];
            // $value = $fieldArray['value'];
            // $fieldArray['widget_id'] = $widget->id;
            // $fieldArray['key'] = $fieldArray['type'] . '-' . $i;
            // $fieldArray['order'] = $i;
            // $allResultArray[] = $fieldArray;
            $field = new Field;
            $field->widget_id = $widget->id;
            $field->key = $fieldArray['type'] . '-' . $i;
            $field->order = $i;
            $field->save();
            $i++;
        }
        // Field::insert($allResultArray);
    }
    
    private static function oneWord()
    {
        return 'ipsum';
    }
    
    private static function shortText()
    {
        return 'Lorem ipsum dolor sit, amet';
    }
    
    private static function mediumText()
    {
        return 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cupiditate, quidem neque cumque ipsa doloremque dolor animi eius, repellat consectetur, quas adipisci magnam tenetur est nam amet facere expedita saepe eum.';
    }
    
    private static function longText()
    {
        return <<<EOD
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cupiditate, quidem neque cumque ipsa doloremque dolor animi eius, repellat consectetur, quas adipisci magnam tenetur est nam amet facere expedita saepe eum.</p>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cupiditate, quidem neque cumque ipsa doloremque dolor animi eius, repellat consectetur, quas adipisci magnam tenetur est nam amet facere expedita saepe eum.</p>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cupiditate, quidem neque cumque ipsa doloremque dolor animi eius, repellat consectetur, quas adipisci magnam tenetur est nam amet facere expedita saepe eum.</p>
        EOD;
    }
    
    private static function smallImage()
    {
        return '/themelight-bootstrap-main/img/blog-details-img4.jpg';
    }

    private static function mediumImage()
    {
        return '/themelight-bootstrap-main/img/blog/blog-2.jpg';
    }

    private static function largeImage()
    {
        return '/themelight-bootstrap-main/img/earth.jpg';
    }

    private static function wideImage()
    {
        return '/themelight-bootstrap-main/img/counter-bg.jpg';
    }
}