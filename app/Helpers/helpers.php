<?php


if (!function_exists('inputerror')) {
    function inputerror($name,$errors){
        return $errors->has($name) ? 'is-invalid' : '';
    }
}

if (!function_exists('alerterror')) {
    function alerterror($name, $errors){
        if ($errors->has($name)) {
            return '<div class="invalid-feedback">'.$errors->first($name).'</div>';
        }
        return '';
    }
}

if (! function_exists('validationResult')) {
    function validationResult($inputname,$errors)
    {
        if($errors->has($inputname)){
            return '<div class="error">'.$errors->first($inputname).'</div>';
        }
    }
}

if (! function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}
