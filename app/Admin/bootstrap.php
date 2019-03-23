<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use \Encore\Admin\Grid\Column;
use \App\Exceptions\Fitout;
Encore\Admin\Form::forget(['map', 'editor']);
Column::extend('fitout', Fitout::class);
Column::extend('money', function ($value) {
    return number_format($value,2);
});
Column::extend('money', function ($value) {
    return number_format($value,2);
});
Column::extend('rate', function ($value) {
    return ($value*100).'%';
});