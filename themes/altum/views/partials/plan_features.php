<?php defined('ALTUMCODE') || die() ?>

<ul class="list-style-none m-0">
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->stores_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->stores_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->stores_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_stores_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->stores_limit, '<strong>' . nr($data->plan_settings->stores_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->menus_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->menus_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->menus_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_menus_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->menus_limit, '<strong>' . nr($data->plan_settings->menus_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->categories_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->categories_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->categories_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_categories_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->categories_limit, '<strong>' . nr($data->plan_settings->categories_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->items_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->items_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_items_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->items_limit, '<strong>' . nr($data->plan_settings->items_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>

    <?php if($this->settings->stores->domains_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->domains_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->domains_limit ? null : 'text-muted' ?>">
            <?php if($data->plan_settings->domains_limit == -1): ?>
                <?= \Altum\Language::get()->global->plan_settings->unlimited_domains_limit ?>
            <?php else: ?>
                <?= sprintf(\Altum\Language::get()->global->plan_settings->domains_limit, '<strong>' . nr($data->plan_settings->domains_limit) . '</strong>') ?>
            <?php endif ?>
        </div>
    </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->ordering_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->ordering_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->ordering_is_enabled ?>
        </div>
    </li>

    <?php if($this->settings->stores->additional_domains_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->additional_domains_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->additional_domains_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->additional_domains_is_enabled ?>
        </div>
    </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->analytics_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->analytics_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->analytics_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->password_protection_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->password_protection_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->removable_branding_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_url_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_url_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->custom_url_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->search_engine_block_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->search_engine_block_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_css_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_css_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->custom_css_is_enabled ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->custom_js_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->custom_js_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->custom_js_is_enabled ?>
        </div>
    </li>

    <?php if($this->settings->stores->email_reports_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->email_reports_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->email_reports_is_enabled ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->email_reports_is_enabled ?>
        </div>
    </li>
    <?php endif ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->no_ads ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
            <?= \Altum\Language::get()->global->plan_settings->no_ads ?>
        </div>
    </li>
</ul>
