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
                <li class="active" aria-current="page"><?= $this->language->store->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <div>
                <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->store->header, $data->store->name) ?></h1>
            </div>

            <p>
                <a href="<?= $data->store->full_url ?>" target="_blank">
                    <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->store->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

                    <?= $data->store->full_url ?>
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
                        data-clipboard-text="<?= $data->store->full_url ?>"
                >
                    <i class="fa fa-fw fa-sm fa-copy"></i>
                </button>
            </p>
        </div>
    </div>

    <div class="d-flex align-items-center mb-3">
        <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= $this->language->menu->menus ?></h2>

        <div class="flex-fill">
            <hr class="border-gray-100" />
        </div>

        <div class="ml-3">
            <?php if($this->user->plan_settings->menus_limit != -1 && $data->total_menus >= $this->user->plan_settings->menus_limit): ?>
                <button type="button" data-confirm="<?= $this->language->menu->error_message->menus_limit ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->menu->create ?>
                </button>
            <?php else: ?>
                <a href="<?= url('menu-create/' . $data->store->store_id) ?>" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->menu->create ?></a>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->menus)): ?>
        <div class="row">

            <?php foreach($data->menus as $row): ?>
                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between">
                                <h3 class="h4 card-title">
                                    <a href="<?= url('menu/' . $row->menu_id) ?>"><?= $row->name ?></a>
                                </h3>

                                <?= include_view(THEME_PATH . 'views/menu/menu_dropdown_button.php', ['id' => $row->menu_id, 'external_url' => url('s/' . $data->store->url . '/' . $row->url)]) ?>
                            </div>

                            <p class="m-0">
                                <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <i class="fa fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf($this->language->menu->datetime, \Altum\Date::get($row->datetime, 2)) ?>
                                </small>
                            </p>
                        </div>

                        <div class="card-footer bg-gray-50 border-0">
                            <div class="d-flex flex-lg-row justify-content-lg-between">
                                <div>
                                    <i class="fa fa-fw fa-sm fa-chart-pie text-muted mr-1"></i> <a href="<?= url('statistics?menu_id=' . $row->menu_id) ?>"><?= sprintf($this->language->menu->pageviews, nr($row->pageviews)) ?></a>
                                </div>

                                <div>
                                    <?php if($row->is_enabled): ?>
                                        <span class="badge badge-success"><i class="fa fa-fw fa-check"></i> <?= $this->language->global->active ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= $this->language->global->disabled ?></span>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->store->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->store->no_data ?></h2>
            <p class="text-muted"><?= $this->language->store->no_data_help ?></p>
        </div>

    <?php endif ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


