<?php

function generateSelect($id, $options, $icon){
    $select = "
    <div class='select has-icons-left is-fullwidth' style='width: 100%;'>
        <div class='select' style='width: 100%;'>
            <select id='$id' name='$id' style='width: 100%;'>
                {$options}
            </select>
        </div>
        <div class='icon is-small is-left'>
           {$icon}
        </div>
    </div>
    ";
    return $select;
}