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
                    <a href="<?= url('store/' . $data->store->store_id) ?>"><?= $this->language->store->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= $this->language->orders->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->orders->header, $data->store->name) ?></h1>
    <p>
        <a href="<?= url('s/' . $data->store->url) ?>" target="_blank">
            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url(url('s/' . $data->store->url))['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

            <?= url('s/' . $data->store->url) ?>
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
                data-clipboard-text="<?= url('s/' . $data->store->url) ?>"
        >
            <i class="fa fa-fw fa-sm fa-copy"></i>
        </button>
    </p>

    <?php if(count($data->orders)): ?>
        <div class="row">

            <?php foreach($data->orders as $row): ?>
                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between">
                                <h3 class="h4 card-title">
                                    <a href="<?= url('order/' . $row->order_id) ?>"><?= $this->language->orders->view ?></a>
                                </h3>

                                <?= ''// include_view(THEME_PATH . 'views/menu/menu_dropdown_button.php', ['id' => $row->menu_id, 'external_url' => url('s/' . $data->store->url . '/' . $row->url)]) ?>
                            </div>

                            <p class="m-0">
                                <small class="text-muted">
                                    <i class="fa fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= sprintf($this->language->orders->price_currency, $row->price, $data->store->currency) ?>
                                </small>
                            </p>

                            <p class="m-0">
                                <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <i class="fa fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf($this->language->orders->datetime, \Altum\Date::get($row->datetime, 2)) ?>
                                </small>
                            </p>
                        </div>

                        <div class="card-footer bg-gray-50 border-0">
                            <div class="d-flex flex-lg-row justify-content-lg-between">
                                <div>
                                    <span class="badge badge-primary"><?= $this->language->order->{'type_' . $row->type} ?></span>
                                </div>

                                <div>
                                    <?php if($row->status): ?>
                                        <span class="badge badge-success"><i class="fa fa-fw fa-check"></i> <?= $this->language->order->status_complete ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa fa-fw fa-clock"></i> <?= $this->language->order->status_pending ?></span>
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
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->orders->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->orders->no_data ?></h2>
            <p class="text-muted"><?= $this->language->orders->no_data_help ?></p>
        </div>

    <?php endif ?>
</div>


<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


