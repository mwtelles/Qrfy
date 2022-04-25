<?php defined('ALTUMCODE') || die() ?>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<div class="container">
    <?php display_notifications() ?>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <h1 class="h4 text-truncate"><?= $this->language->dashboard->header ?></h1>
        </div>
    </div>

    <div class="d-flex align-items-center mb-3">
        <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= $this->language->store->stores ?></h2>

        <div class="flex-fill">
            <hr class="border-gray-100" />
        </div>

        <div class="ml-3">
            <?php if($this->user->plan_settings->stores_limit != -1 && $data->total_stores >= $this->user->plan_settings->stores_limit): ?>
                <button type="button" data-confirm="<?= $this->language->store->error_message->stores_limit ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->store->create ?>
                </button>
            <?php else: ?>
                <a href="<?= url('store-create') ?>" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->store->create ?></a>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->stores)): ?>
        <div class="row">

            <?php foreach($data->stores as $row): ?>
                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between">
                                <h3 class="h4 card-title">
                                    <a href="<?= url('store/' . $row->store_id) ?>"><?= $row->name ?></a>
                                </h3>

                                <?= include_view(THEME_PATH . 'views/store/store_dropdown_button.php', ['id' => $row->store_id, 'external_url' => url('s/' . $row->url)]) ?>
                            </div>

                            <p class="m-0">
                                <small class="text-muted">
                                    <i class="fa fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <a href="<?= $row->full_url ?>" target="_blank"><?= $row->full_url ?></a>
                                </small>
                            </p>
                            <p class="m-0">
                                <small class="text-muted">
                                    <i class="fa fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= sprintf($this->language->store->currency, $row->currency) ?>
                                </small>
                            </p>
                            <p class="m-0">
                                <small class="text-muted">
                                    <i class="fa fa-fw fa-sm fa-clock text-muted mr-1"></i> <?= sprintf($this->language->store->timezone, $row->timezone) ?>
                                </small>
                            </p>
                            <p class="m-0">
                                <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <i class="fa fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf($this->language->store->datetime, \Altum\Date::get($row->datetime, 2)) ?>
                                </small>
                            </p>
                        </div>

                        <div class="card-footer bg-gray-50 border-0">
                            <div class="d-flex flex-lg-row justify-content-lg-between">
                                <div>
                                    <i class="fa fa-fw fa-sm fa-chart-pie text-muted mr-1"></i> <a href="<?= url('statistics?store_id=' . $row->store_id) ?>"><?= sprintf($this->language->store->pageviews, nr($row->pageviews)) ?></a>
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
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->dashboard->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->dashboard->no_data ?></h2>
            <p class="text-muted"><?= $this->language->dashboard->no_data_help ?></p>
        </div>

    <?php endif ?>
</div>

