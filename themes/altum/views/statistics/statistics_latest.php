<?php defined('ALTUMCODE') || die() ?>

<div class="card">
    <div class="card-body">
        <h3 class="h5"><?= $this->language->statistics->statistics->latest ?></h3>
        <p class="text-muted mb-4"><?= $this->language->statistics->statistics->latest_help ?></p>

        <?php foreach($data->rows as $row): ?>
            <div class="row">
                <div class="col-2 col-md-1">
                    <?php
                    $visit_type = 'store_id';
                    $icon = 'fa-store';

                    if($row->menu_id) {
                        $visit_type = 'menu_id';
                        $icon = 'fa-list';
                    }
                    if($row->category_id) {
                        $visit_type = 'category_id';
                        $icon = 'fa-shopping-bag';
                    }
                    if($row->item_id) {
                        $visit_type = 'item_id';
                        $icon = 'fa-burn';
                    }
                    ?>
                    <i class="fa fa-fw <?= $icon ?> fa-lg mr-3"></i>
                </div>

                <div class="col-10 col-md-5">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center mr-3">
                            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/countries/' . (!empty($row->country_code) ? strtolower($row->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                            <span class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : $this->language->statistics->statistics->country_unknown ?></span>
                        </div>

                        <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get_timeago($row->datetime) ?></small>
                    </div>

                    <div class="text-truncate">
                        <?php if(!$row->referrer_host): ?>
                            <small class="text-muted"><?= $this->language->statistics->statistics->referrer_direct ?></small>
                        <?php elseif($row->referrer_host == 'qr'): ?>
                            <small class="text-muted"><?= $this->language->statistics->statistics->referrer_qr ?></small>
                        <?php else: ?>
                            <img src="https://external-content.duckduckgo.com/ip3/<?= $row->referrer_host ?>.ico" class="img-fluid icon-favicon mr-1" />
                            <small><a href="<?= $row->referrer_host . $row->referrer_path ?>" title="<?= $row->referrer_host . $row->referrer_path ?>" class="text-muted align-middle"><?= $row->referrer_host . $row->referrer_path ?></a></small>
                        <?php endif ?>
                    </div>
                </div>

                <div class="d-none d-md-block col-3">
                    <div class="d-flex align-items-center">
                        <?php if($row->device_type): ?>
                            <i class="fa fa-fw fa-sm fa-<?= $row->device_type ?> mr-1"></i>
                            <?= $this->language->statistics->statistics->{'device_' . $row->device_type} ?>
                        <?php else: ?>
                            <?= $this->language->statistics->statistics->device_unknown ?>
                        <?php endif ?>
                    </div>

                    <small class="text-muted">
                        <?php if($row->os_name): ?>
                            <?= $row->os_name ?>
                        <?php else: ?>
                            <?= $this->language->statistics->statistics->os_unknown ?>
                        <?php endif ?>
                    </small>
                </div>

                <div class="d-none d-md-block col-3">
                    <div class="d-flex">
                        <?php if($row->browser_name): ?>
                            <?= $row->browser_name ?>
                        <?php else: ?>
                            <?= $this->language->statistics->statistics->browser_unknown ?>
                        <?php endif ?>
                    </div>

                    <small class="text-muted">
                        <?php if($row->browser_language): ?>
                            <?= get_language_from_locale($row->browser_language) ?>
                        <?php else: ?>
                            <?= $this->language->statistics->statistics->language_unknown ?>
                        <?php endif ?>
                    </small>
                </div>
            </div>

            <hr class="border-gray-50 my-3" />
        <?php endforeach ?>
    </div>
</div>
