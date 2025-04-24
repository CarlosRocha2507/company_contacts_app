<?php

function generateTale($title, $header_button, $head, $rows)
{
    $table = "
        <section class='hero is-hero-bar'>
            <div class='hero-body'>
            <div class='level'>
                <div class='level-left'>
                <div class='level-item'>
                    <h1 class='title'>
                    {$title}
                    </h1>
                </div>
                </div>
                <div class='level-right' style='display: none;'>
                <div class='level-item'></div>
                </div>
            </div>
            </div>
        </section>
        <div class='columns'>
            
            <div class='column'>
            <div class='card has-table'>
                <header class='card-header'>
                <p class='card-header-title'>
                <span class='icon'><i class='mdi mdi-account-multiple'></i></span>
                    {$title}
                </p>
                
                {$header_button}
                </header>
                <div class='card has-table has-table-container-upper-radius'>
                <div class='card-content'>
                    <div class='b-table has-pagination'>
                    <div class='table-wrapper has-mobile-cards'>
                        <table class='table is-fullwidth is-striped is-hoverable is-fullwidth'>
                        <thead>
                            {$head}
                        </thead>
                        <tbody>
                            {$rows}
                        </tbody>
                        </table>
                    </div>
                    <div class='notification'>
                        <div class='level'>
                        <div class='level-left'>
                            <div class='level-item'>
                              <div id='pagination-controls' class='buttons has-addons'></div>
                            </div>
                        </div>
                        <div class='level-right'>
                            <div class='level-item' id='pagination-level'>
                            <small>Page 1 of 3</small>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pagination logic
                let rowsPerPage = 6; // Número de linhas por página
                let table = document.querySelector('table tbody');
                let rows = table.querySelectorAll('tr');
                let totalRows = rows.length;
                let totalPages = Math.ceil(totalRows / rowsPerPage);
                let currentPage = 1;

                // Show the actual page to user
                function showPage(page) {
                    let start = (page - 1) * rowsPerPage;
                    let end = start + rowsPerPage;

                    rows.forEach((row, index) => {
                        row.style.display = (index >= start && index < end) ? 'table-row' : 'none';
                    });

                    document.querySelector('#pagination-level small').innerText = 'Página ' + page + ' de ' + totalPages;
                }

                // Create pagination buttons
                function createPagination() {
                    let paginationDiv = document.querySelector('#pagination-controls');
                    paginationDiv.innerHTML = '';

                    for (let i = 1; i <= totalPages; i++) {
                        let button = document.createElement('button');
                        button.classList.add('button');
                        button.innerText = i;
                        button.addEventListener('click', function() {
                            currentPage = i;
                            showPage(currentPage);
                        });

                        paginationDiv.appendChild(button);
                    }
                }

                // Create buttons and show the first page
                createPagination();
                showPage(currentPage);
            });
            </script>
    ";
    return $table;
}

function generateTaleWithFilter($title, $filters, $header_button, $head, $rows)
{
    $table = "
        <section class='hero is-hero-bar'>
            <div class='hero-body'>
            <div class='level'>
                <div class='level-left'>
                <div class='level-item'>
                    <h1 class='title'>
                    {$title}
                    </h1>
                </div>
                </div>
                <div class='level-right' style='display: none;'>
                <div class='level-item'></div>
                </div>
            </div>
            </div>
        </section>
        <div class='columns' style='height: 100%;'>
    <div class='column is-one-quarter'>
                <div class='box'>
                    <h2 class='title is-5'>Filters</h2>
                    <div class='field has-addons'>
                        <div class='control' style='flex: 1;'>
                            <input class='input' id='searchInput' type='text' placeholder='Search for something...' style='width: 100%;'>
                        </div>
                        <div class='control'>
                            <a class='button is-info' style='height: 100%;'>
                            <i class='fa-solid fa-magnifying-glass is-fullheigh'></i>
                            </a>
                        </div>
                    </div>
                    {$filters}
                    <div class='field' style='margin-top: 2%;'>
                    <div class='control'>
                        <button class='button is-primary' onclick='applyFilter();'>Apply filters</button>
                        <button class='button is-danger' onclick='clearFilters();'>Clear filters</button>
                    </div>
                    </div>
                </div>
                </div>
            <div class='column'>
            <div class='card has-table'>
                <header class='card-header'>
                <p class='card-header-title'>
                <span class='icon'><i class='mdi mdi-account-multiple'></i></span>
                    {$title}
                </p>
                
                {$header_button}
                </header>
                <div class='card has-table has-table-container-upper-radius'>
                <div class='card-content'>
                    <div class='b-table has-pagination'>
                    <div class='table-wrapper has-mobile-cards'>
                        <table class='table is-fullwidth is-striped is-hoverable is-fullwidth' id='search-table'>
                        <thead>
                            {$head}
                        </thead>
                        <tbody>
                            {$rows}
                        </tbody>
                        </table>
                    </div>
                    <div class='notification'>
                        <div class='level'>
                        <div class='level-left'>
                            <div class='level-item'>
                              <div id='pagination-controls' class='buttons has-addons'></div>
                            </div>
                        </div>
                        <div class='level-right'>
                            <div class='level-item' id='pagination-level'>
                            <small>Page 1 of 3</small>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pagination logic
                let rowsPerPage = 6; // Number of rows per page
                let table = document.querySelector('table tbody');
                let rows = table.querySelectorAll('tr');
                let totalRows = rows.length;
                let totalPages = Math.ceil(totalRows / rowsPerPage);
                let currentPage = 1;

                // Show the actual page to user
                function showPage(page) {
                    let start = (page - 1) * rowsPerPage;
                    let end = start + rowsPerPage;

                    rows.forEach((row, index) => {
                        row.style.display = (index >= start && index < end) ? 'table-row' : 'none';
                    });

                    document.querySelector('#pagination-level small').innerText = 'Page ' + page + ' of ' + totalPages;
                }

                // Create pagination buttons
                function createPagination() {
                    let paginationDiv = document.querySelector('#pagination-controls');
                    paginationDiv.innerHTML = '';

                    for (let i = 1; i <= totalPages; i++) {
                        let button = document.createElement('button');
                        button.classList.add('button');
                        button.innerText = i;
                        button.addEventListener('click', function() {
                            currentPage = i;
                            showPage(currentPage);
                        });

                        paginationDiv.appendChild(button);
                    }
                }

                // Create buttons and show the first page
                createPagination();
                showPage(currentPage);
            });
            </script>";
    return $table;
}

function generaSimpleTable($title, $head, $rows)
{
    $table = "
        <div class='card has-table' style='overflow: auto;min-height: 500px;'>
            <header class='card-header'>
                <p class='card-header-title'>
                    <span class='icon'><i class='mdi mdi-account-multiple'></i></span>
                    {$title}
                </p>
            </header>
            <div class='card has-table has-table-container-upper-radius'>
                <div class='card-content'>
                    <div class='b-table has-pagination'>
                        <div class='table-wrapper has-mobile-cards is-overlay'>
                            <table class='table is-fullwidth is-striped is-hoverable is-fullwidth'>
                                <thead>
                                    {$head}
                                </thead>
                                <tbody>
                                    {$rows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    return $table;
}