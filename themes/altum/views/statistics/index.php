<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?php display_notifications() ?>

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= url('dashboard') ?>"><?= $this->language->dashboard->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= url('store/' . $data->{$data->identifier_name}->store_id) ?>"><?= $this->language->store->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <?php if(in_array($data->identifier_name, ['menu', 'category', 'item'])): ?>
                    <li>
                        <a href="<?= url('menu/' . $data->{$data->identifier_name}->menu_id) ?>"><?= $this->language->menu->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                    </li>

                    <?php if(in_array($data->identifier_name, ['category', 'item'])): ?>
                        <li>
                            <a href="<?= url('category/' . $data->{$data->identifier_name}->category_id) ?>"><?= $this->language->category->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                        </li>
                    <?php endif ?>

                    <?php if(in_array($data->identifier_name, ['item'])): ?>
                        <li>
                            <a href="<?= url('item/' . $data->{$data->identifier_name}->item_id) ?>"><?= $this->language->item->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                        </li>
                    <?php endif ?>
                <?php endif ?>
                <li class="active" aria-current="page"><?= $this->language->statistics->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between mb-3">
        <div>
            <div>
                <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->statistics->header, $data->{$data->identifier_name}->name) ?></h1>
            </div>

            <p>
                <a href="<?= $data->external_url ?>" target="_blank">
                    <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->external_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

                    <?= $data->external_url ?>
                </a>

                <button
                        id="url_copy"
                        type="button"
                        class="btn btn-link"
                        data-toggle="tooltip"
                        title="<?= $this->language->global->clipboard_copy ?>"
                        aria-label="<?= $this->language->global->clipboard_copy ?>"
                        data-copy="<?= $this->language->global->clipboard_copy ?>"
                        data-copied="<?= $this->language->global->clipboard_copied ?>"
                        data-clipboard-text="<?= $data->external_url ?>"
                >
                    <i class="fa fa-fw fa-sm fa-copy"></i>
                </button>
            </p>
        </div>

        <div>
            <button
                id="daterangepicker"
                type="button"
                class="btn btn-sm btn-outline-primary"
                data-min-date="<?= (new \DateTime($data->{$data->identifier_name}->datetime))->format('Y-m-d') ?>"
                data-max-date="<?= (new \DateTime())->format('Y-m-d') ?>"
            >
                <i class="fa fa-fw fa-calendar mr-1"></i>
                <span>
                    <?php if($data->date->start_date == $data->date->end_date): ?>
                        <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) ?>
                    <?php else: ?>
                        <?= \Altum\Date::get($data->date->start_date, 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->date->end_date, 2, \Altum\Date::$default_timezone) ?>
                    <?php endif ?>
                </span>
                <i class="fa fa-fw fa-caret-down ml-1"></i>
            </button>
        </div>
    </div>

    <?php if(!count($data->pageviews)): ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->statistics->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->statistics->no_data ?></h2>
            <p class="text-muted"><?= $this->language->statistics->no_data_help ?></p>
        </div>

    <?php else: ?>

        <div class="chart-container mb-5">
            <canvas id="pageviews_chart"></canvas>
        </div>

        <ul class="nav nav-pills flex-column flex-lg-row mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'latest' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=latest&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-list mr-1"></i>
                    <?= $this->language->statistics->statistics->latest ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'referrer' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=referrer&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-random mr-1"></i>
                    <?= $this->language->statistics->statistics->referrer ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'country' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=country&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-globe mr-1"></i>
                    <?= $this->language->statistics->statistics->country ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'device' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=device&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-laptop mr-1"></i>
                    <?= $this->language->statistics->statistics->device ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'browser' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=browser&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-window-restore mr-1"></i>
                    <?= $this->language->statistics->statistics->browser ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'language' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=language&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-language mr-1"></i>
                    <?= $this->language->statistics->statistics->language ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $data->type == 'os' ? 'active' : null ?>" href="<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=os&start_date=' . $data->date->start_date . '&end_date=' . $data->date->end_date) ?>">
                    <i class="fa fa-fw fa-sm fa-server mr-1"></i>
                    <?= $this->language->statistics->statistics->os ?>
                </a>
            </li>
        </ul>

        <?= $this->views['statistics'] ?>

    <?php endif ?>

    <?php ob_start() ?>
    <link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen">
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php ob_start() ?>
    <script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/Chart.bundle.min.js' ?>"></script>
    <script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment.min.js' ?>"></script>
    <script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/daterangepicker.min.js' ?>"></script>

    <script>
        'use strict';

        /* Daterangepicker */
        $('#daterangepicker').daterangepicker({
            startDate: <?= json_encode($data->date->start_date) ?>,
            endDate: <?= json_encode($data->date->end_date) ?>,
            minDate: $('#daterangepicker').data('min-date'),
            maxDate: $('#daterangepicker').data('max-date'),
            ranges: {
                <?= json_encode($this->language->global->date->today) ?>: [moment(), moment()],
                <?= json_encode($this->language->global->date->yesterday) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                <?= json_encode($this->language->global->date->last_7_days) ?>: [moment().subtract(6, 'days'), moment()],
                <?= json_encode($this->language->global->date->last_30_days) ?>: [moment().subtract(29, 'days'), moment()],
                <?= json_encode($this->language->global->date->this_month) ?>: [moment().startOf('month'), moment().endOf('month')],
                <?= json_encode($this->language->global->date->last_month) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            alwaysShowCalendars: true,
            singleCalendar: true,
            locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
        }, (start, end, label) => {

            /* Redirect */
            redirect(`<?= url('statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&type=' . $data->type) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

        });

        <?php if(count($data->pageviews)): ?>
        /* Default chart options */
        Chart.defaults.global.elements.line.borderWidth = 4;
        Chart.defaults.global.elements.point.radius = 3;
        Chart.defaults.global.elements.point.borderWidth = 6;

        let chart_options = {
            elements: {
                line: {
                    tension: 0
                }
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: (tooltipItem, data) => {
                        let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

                        return `${nr(value)} - ${data.datasets[tooltipItem.datasetIndex].label}`;
                    }
                }
            },
            legend: {
                display: false
            },
            title: {
                text: '',
                display: true
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        beginAtZero: true,
                        userCallback: (value, index, values) => {
                            if (Math.floor(value) === value) {
                                return nr(value);
                            }
                        },
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        callback: (tick, index, array) => {
                            return index % 2 ? '' : tick;
                        }
                    }
                }]
            },
            responsive: true,
            maintainAspectRatio: false
        };

        let css = window.getComputedStyle(document.body)

        /* Pageviews chart */
        let pageviews_chart = document.getElementById('pageviews_chart').getContext('2d');

        let pageviews_color = css.getPropertyValue('--primary');
        let pageviews_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
        pageviews_gradient.addColorStop(0, 'rgba(102, 127, 234, .1)');
        pageviews_gradient.addColorStop(1, 'rgba(102, 127, 234, 0.025)');

        /* Display chart */
        new Chart(pageviews_chart, {
            type: 'line',
            data: {
                labels: <?= $data->pageviews_chart['labels'] ?>,
                datasets: [{
                    label: <?= json_encode($this->language->statistics->pageviews) ?>,
                    data: <?= $data->pageviews_chart['pageviews'] ?? '[]' ?>,
                    backgroundColor: pageviews_gradient,
                    borderColor: pageviews_color,
                    fill: true
                }]
            },
            options: chart_options
        });

        <?php endif ?>
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


