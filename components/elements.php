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

function h1($text, $classes)
{
    $styleClasses = 'text-3xl font-semibold mb-6';
    echo '<h1 ';
    applyClasses($styleClasses, $classes);
    echo '>';
    echo $text;
    echo '</h1>';
}


function input($type, $name,$classes, $placeholder= '')
{
    $styleClasses = 'mt-1 p-2 w-full border rounded-md  focus:outline-none';
    echo '<input ';
    echo 'type="'.$type.'" ';
    echo 'id="'.$name.'" '.'name="'.$name.'" ';
    echo 'placeholder="'.$placeholder.'" ';
    applyClasses($styleClasses,$classes);
    echo '>';
}

?>