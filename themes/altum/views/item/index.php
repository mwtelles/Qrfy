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
                <li>
                    <a href="<?= url('menu/' . $data->menu->menu_id) ?>"><?= $this->language->menu->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= url('category/' . $data->category->category_id) ?>"><?= $this->language->category->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= $this->language->item->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <div class="d-flex align-items-baseline">
                <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->item->header, $data->item->name) ?></h1>
                <?= include_view(THEME_PATH . 'views/item/item_dropdown_button.php', ['id' => $data->item->item_id, 'external_url' => $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url]) ?>
            </div>

            <p>
                <a href="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url ?>" target="_blank">
                    <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url(url('s/' . $data->store->url))['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

                    <?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url ?>
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
                        data-clipboard-text="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url ?>"
                >
                    <i class="fa fa-fw fa-sm fa-copy"></i>
                </button>
            </p>
        </div>
    </div>


    <?php if($data->item->variants_is_enabled): ?>
        <div class="d-flex align-items-center mb-3">
            <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= $this->language->item_option->item_options ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('item-option-create/' . $data->item->item_id) ?>" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->item_option->create ?></a>
            </div>
        </div>

        <?php if(count($data->item_options)): ?>
            <div class="row">

                <?php foreach($data->item_options as $row): ?>
                    <div class="col-12 col-md-6 col-xl-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between">
                                    <h3 class="h4 card-title">
                                        <?= $row->name ?>
                                    </h3>

                                    <?= include_view(THEME_PATH . 'views/item-option/item_option_dropdown_button.php', ['id' => $row->item_option_id]) ?>
                                </div>

                                <p class="m-0">
                                    <small class="text-muted">
                                        <i class="fa fa-fw fa-sm fa-bars text-muted mr-1"></i> <?= sprintf($this->language->item_option->options, implode(', ', $row->options)) ?>
                                    </small>
                                </p>
                                <p class="m-0">
                                    <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                        <i class="fa fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf($this->language->category->datetime, \Altum\Date::get($row->datetime, 2)) ?>
                                    </small>
                                </p>
                            </div>

                            <div class="card-footer bg-gray-50 border-0">
                                <div class="d-flex flex-lg-row justify-content-lg-between">
                                    <div>
                                    </div>

                                    <div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

        <?php else: ?>

            <div class="d-flex flex-column align-items-center justify-content-center">
                <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->item_extra->no_data ?>" />
                <h2 class="h4 text-muted mt-3"><?= $this->language->item_extra->no_data ?></h2>
                <p class="text-muted"><?= $this->language->item_extra->no_data_help ?></p>
            </div>

        <?php endif ?>


        <div class="d-flex align-items-center mb-3">
            <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= $this->language->item_variant->item_variants ?></h2>

            <div class="flex-fill">
                <hr class="border-gray-100" />
            </div>

            <div class="ml-3">
                <a href="<?= url('item-variant-create/' . $data->item->item_id) ?>" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->item_variant->create ?></a>
            </div>
        </div>

        <?php if(count($data->item_variants)): ?>
            <div class="row">

                <?php foreach($data->item_variants as $row): ?>
                    <div class="col-12 col-md-6 col-xl-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between">
                                    <h3 class="h4 card-title"></h3>

                                    <?= include_view(THEME_PATH . 'views/item-variant/item_variant_dropdown_button.php', ['id' => $row->item_variant_id]) ?>
                                </div>

                                <div class="mb-3">
                                    <?php foreach($row->item_options_ids as $item_option_id): ?>
                                        <p class="m-0">
                                            <strong><?= $data->item_options[$item_option_id->item_option_id]->name ?>:</strong> <span><?= $data->item_options[$item_option_id->item_option_id]->options[$item_option_id->option] ?></span>
                                        </p>
                                    <?php endforeach ?>
                                </div>

                                <p class="m-0">
                                    <small class="text-muted">
                                        <i class="fa fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= sprintf($this->language->item_extra->price_currency, $row->price, $data->store->currency) ?>
                                    </small>
                                </p>
                                <p class="m-0">
                                    <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                        <i class="fa fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf($this->language->category->datetime, \Altum\Date::get($row->datetime, 2)) ?>
                                    </small>
                                </p>
                            </div>

                            <div class="card-footer bg-gray-50 border-0">
                                <div class="d-flex flex-lg-row justify-content-lg-between">
                                    <div>
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

        <?php else: ?>

            <div class="d-flex flex-column align-items-center justify-content-center">
                <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->item_extra->no_data ?>" />
                <h2 class="h4 text-muted mt-3"><?= $this->language->item_extra->no_data ?></h2>
                <p class="text-muted"><?= $this->language->item_extra->no_data_help ?></p>
            </div>

        <?php endif ?>
    <?php endif ?>



    <div class="d-flex align-items-center mb-3">
        <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= $this->language->item_extra->item_extras ?></h2>

        <div class="flex-fill">
            <hr class="border-gray-100" />
        </div>

        <div class="ml-3">
            <a href="<?= url('item-extra-create/' . $data->item->item_id) ?>" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->item_extra->create ?></a>
        </div>
    </div>

    <?php if(count($data->item_extras)): ?>
        <div class="row">

            <?php foreach($data->item_extras as $row): ?>
                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between">
                                <h3 class="h4 card-title">
                                   <?= $row->name ?>
                                </h3>

                                <?= include_view(THEME_PATH . 'views/item-extra/item_extra_dropdown_button.php', ['id' => $row->item_extra_id]) ?>
                            </div>

                            <p class="m-0">
                                <small class="text-muted">
                                    <i class="fa fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= sprintf($this->language->item_extra->price_currency, $row->price, $data->store->currency) ?>
                                </small>
                            </p>
                            <p class="m-0">
                                <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <i class="fa fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf($this->language->category->datetime, \Altum\Date::get($row->datetime, 2)) ?>
                                </small>
                            </p>
                        </div>

                        <div class="card-footer bg-gray-50 border-0">
                            <div class="d-flex flex-lg-row justify-content-lg-between">
                                <div>
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

    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->item_extra->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->item_extra->no_data ?></h2>
            <p class="text-muted"><?= $this->language->item_extra->no_data_help ?></p>
        </div>

    <?php endif ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


