<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php display_notifications() ?>

    <?php if(count($data->domains)): ?>
        <div class="row mb-3">
            <div class="col-12 col-xl">
                <h1 class="h4"><?= $this->language->domains->header ?></h1>
                <p class="text-muted"><?= $this->language->domains->subheader ?></p>
            </div>

            <div class="col-12 col-xl-auto">
                <?php if($this->user->plan_settings->domains_limit != -1 && $data->total_domains >= $this->user->plan_settings->domains_limit): ?>
                    <button type="button" data-confirm="<?= $this->language->domains->error_message->domains_limit ?>" class="btn btn-outline-primary">
                        <i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->domains->create ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('domain-create') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= $this->language->domains->create ?></a>
                <?php endif ?>
            </div>
        </div>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th><?= $this->language->domains->table->host ?></th>
                        <th><?= $this->language->domains->table->datetime ?></th>
                        <th><?= $this->language->domains->table->is_enabled ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach($data->domains as $row): ?>

                    <tr>
                        <td>
                            <a href="<?= url('domain-update/' . $row->domain_id) ?>"><?= $row->host ?></a>
                        </td>

                        <td class="text-muted">
                            <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get($row->datetime, 2) ?></span>
                        </td>

                        <td>
                            <?php if($row->is_enabled): ?>
                                <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> <?= $this->language->domains->table->is_enabled_active ?></span>
                            <?php else: ?>
                                <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= $this->language->domains->table->is_enabled_pending ?></span>
                            <?php endif ?>
                        </td>

                        <td>
                            <?= include_view(THEME_PATH . 'views/domains/domain_dropdown_button.php', ['id' => $row->domain_id]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= $this->language->domains->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->domains->no_data ?></h2>
            <p class="text-muted"><?= $this->language->domains->no_data_help ?></p>
        </div>

    <?php endif ?>

</div>
