<?php
function getFilter($filter_title, $filters)
{
    $filter = "
    <div class='column is-one-quarter'>
            <div class='box'>
                <h2 class='title is-5'>{$filter_title}</h2>
                <div class='field has-addons'>
                    <div class='control'>
                        <input class='input' type='text' placeholder='Pesquise por algo...'>
                    </div>
                    <div class='control'>
                        <a class='button is-info' style='height: 100%;'>
                        <i class='fa-solid fa-magnifying-glass is-fullheigh'></i>
                        </a>
                    </div>
                </div>
                    {$filters}
                <div class='control'>
                    <button class='button is-primary'>Aplicar Filtros</button>
                </div>
                </div>
            </div>
            </div>";
    return $filter;
}