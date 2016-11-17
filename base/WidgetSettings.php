<?php
namespace anda\cms\base;

class WidgetSettings
{
    public static function tinyMce($arr = [])
    {
        $settings = [
            'enableFilemanager' => true,
            'folderName' => ['file'=> 'File', 'image'=>'Image', 'media'=>'Media'],
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }


    public static function DatePicker($arr = [])
    {
        $settings = [
            'options' => ['placeholder' => 'Select operating time ...'],
            'type' => 3,
            'convertFormat' => true,
            'language'=>'th',
            'pluginOptions' => [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'd/M/y',
            ]
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }
    public static function TimePicker($arr = [])
    {
        $settings = [
            'options' => ['placeholder' => 'Select time ...'],
            'pluginOptions' => [
                'showSeconds' => true,
                'showMeridian' => false,
                'minuteStep' => 1,
                'secondStep' => 5,
            ]
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }

    public static function DateTimePicker($arr = [])
    {
        $settings = [
            'options' => ['placeholder' => 'Select operating time ...'],
            'type' => 3,
            'convertFormat' => true,
            'language'=>'th',
            'pluginOptions' => [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'd/M/y H:i:s',
            ]
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }

    public static function TouchSpin($arr = [])
    {
        $settings = [
            'options' => ['placeholder' => 'Adjust...'],
            'pluginOptions' => ['step' => 1]
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }

    public static function SwitchInput($arr = [])
    {
        $settings = [
            'pluginOptions' => ['size' => 'mini'],
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }



    public static function Select2($arr = [])
    {
        $settings = [
            'data' => [],
            'theme' => \kartik\select2\Select2::THEME_DEFAULT,
            'options' => ['placeholder' => 'Select ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }

    public static function ColorInput($arr = [])
    {
        $settings = [
            'options' => ['placeholder' => 'Select color...']
        ];

        $settings = array_replace_recursive($settings, $arr);

        return $settings;
    }
}