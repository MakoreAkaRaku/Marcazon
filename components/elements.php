<?php
function applyClasses($mainClasses, $newlyClasses)
{
    echo 'class="' . implode(' ', [$mainClasses, $newlyClasses]) . '"';
}

function button($type = 'submit', $classes, $text)
{
    $styleClasses = "p-2 rounded-md hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-300";
    echo '<button type="' . $type . '"';
    applyClasses($styleClasses, $classes);
    echo '>';
    echo $text;
    echo "</button>";
}
function darkButton($type = 'submit', $classes, $text)
{
    $darkButtonClasses = 'bg-black text-white focus:ring-gray-900';
    button($type, implode(' ', [$darkButtonClasses, $classes]), $text);
}
function blueButton($type = 'submit', $classes, $text)
{
    $darkButtonClasses = "bg-blue text-white focus:ring-gray-900";
    button($type, implode(' ', [$darkButtonClasses, $classes]), $text);
}

function h1($text, $classes = '')
{
    $styleClasses = 'text-3xl font-semibold mb-6';
    echo '<h1 ';
    applyClasses($styleClasses, $classes);
    echo '>';
    echo $text;
    echo '</h1>';
}


function input($type, $name, $classes,$value = "", $placeholder = '', bool $isRequired = false)
{
    $styleClasses = 'border rounded-md  focus:outline-none';
    echo '<input ';
    echo 'type="' . $type . '" ';
    echo 'id="' . (empty($value) ? $name:$value) . '" ' . 'name="' . $name . '" ';
    if (!empty($value) || $value =="0")
        echo 'value="'. $value.'" ';
    if (!empty($placeholder))
        echo 'placeholder="' . $placeholder . '" ';
    echo $isRequired ? 'required ' : '';
    applyClasses($styleClasses, $classes);
    echo '>';
}

function select($options, $name, $selClasses, $optClasses, bool $isRequired = false)
{
    $styleClasses = "border rounded-md  focus:outline-none";
    echo '<select ';
    echo 'id="' . $name . '" name=" ' . $name . '" ';
    applyClasses($styleClasses, $selClasses);
    echo '>';
    foreach ($options as $key => $opt) {
        echo '<option ';
        applyClasses($styleClasses, $optClasses);
        echo ' value="' . $opt . '">' . $key . '</option>';
    }
    echo '</select>';
}

function a($link, $text, $styleClasses = "")
{
    $basicStyleClasses = '';
    echo '<a href="' . $link . '" ';
    applyClasses($basicStyleClasses, $styleClasses);
    echo '>' . $text . '</a>';
}

function p($text, $styleClasses = "")
{
    $basicStyleClasses = '';
    echo '<p ';
    applyClasses($basicStyleClasses, $styleClasses);
    echo '>' . $text . '</p>';
}

function img($alt, $uri, $styleClasses = "")
{
    $basicStyleClasses = '';
    echo '<img alt="' . $alt . '" ';
    applyClasses($basicStyleClasses, $styleClasses);
    echo 'src="' . $uri . '">';
}

function label($text,$for, $classes)
{
    $basicStyleClasses= '';
    echo '<label for="'.$for.'"';
    applyClasses($basicStyleClasses,$classes);
    echo '>';
    echo $text;
    echo '</label>';
}

function createFormList($elements,$name,$method,$action,$listClass='',$buttonClass='')
{
    $styleClasses = "border rounded-md";
    echo '<ul class="'.$listClass.'">';
    foreach ($elements as $key => $text) {
        echo '<li class="'.$buttonClass.'">';
        echo '<form method="'.$method.'" action="'.$action.'">';
        input("hidden",$name,"",$key);
        echo '<button>';
        echo $text;
        echo '</button>';
        echo "</form>";
        echo '</li>';
    }
    echo '</ul>';
}

?>