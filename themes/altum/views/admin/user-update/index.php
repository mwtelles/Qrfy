<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-user text-primary-900 mr-2"></i> <?= $this->language->admin_user_update->header ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/partials/admin_user_dropdown_button.php', ['id' => $data->user->user_id]) ?>
    </div>
</div>

<?php display_notifications() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_user_update->main->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_user_update->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->name ?></label>
                        <input type="text" name="name" class="form-control form-control-lg" value="<?= $data->user->name ?>" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->email ?></label>
                        <input type="text" name="email" class="form-control form-control-lg" value="<?= $data->user->email ?>" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->is_enabled ?></label>

                        <select class="form-control form-control-lg" name="is_enabled">
                            <option value="2" <?php if($data->user->active == 2) echo 'selected' ?>><?= $this->language->admin_user_update->main->is_enabled_disabled ?></option>
                            <option value="1" <?php if($data->user->active == 1) echo 'selected' ?>><?= $this->language->admin_user_update->main->is_enabled_active ?></option>
                            <option value="0" <?php if($data->user->active == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->is_enabled_unconfirmed ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->main->type ?></label>

                        <select class="form-control form-control-lg" name="type">
                            <option value="1" <?php if($data->user->type == 1) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_admin ?></option>
                            <option value="0" <?php if($data->user->type == 0) echo 'selected' ?>><?= $this->language->admin_user_update->main->type_user ?></option>
                        </select>

                        <small class="form-text text-muted"><?= $this->language->admin_user_update->main->type_help ?></small>
                    </div>
                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_user_update->plan->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_user_update->plan->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->plan->plan_id ?></label>

                        <select class="form-control form-control-lg" name="plan_id">
                            <option value="free" <?php if($data->user->plan->plan_id == 'free') echo 'selected' ?>><?= $this->settings->plan_free->name ?></option>
                            <option value="trial" <?php if($data->user->plan->plan_id == 'trial') echo 'selected' ?>><?= $this->settings->plan_trial->name ?></option>
                            <option value="custom" <?php if($data->user->plan->plan_id == 'custom') echo 'selected' ?>><?= $this->settings->plan_custom->name ?></option>

                            <?php while($row = $data->plans_result->fetch_object()): ?>
                                <option value="<?= $row->plan_id ?>" <?php if($data->user->plan->plan_id == $row->plan_id) echo 'selected' ?>><?= $row->name ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->plan->plan_trial_done ?></label>

                        <select class="form-control form-control-lg" name="plan_trial_done">
                            <option value="1" <?= $data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= $this->language->global->yes ?></option>
                            <option value="0" <?= !$data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= $this->language->global->no ?></option>
                        </select>
                    </div>

                    <div id="plan_expiration_date_container" class="form-group">
                        <label><?= $this->language->admin_user_update->plan->plan_expiration_date ?></label>
                        <input type="text" class="form-control form-control-lg" name="plan_expiration_date" autocomplete="off" value="<?= $data->user->plan_expiration_date ?>">
                        <div class="invalid-feedback">
                            <?= $this->language->admin_user_update->plan->plan_expiration_date_invalid ?>
                        </div>
                    </div>

                    <div id="plan_settings" style="display: none">
                        <div class="form-group">
                            <label for="stores_limit"><?= $this->language->admin_plans->plan->stores_limit ?></label>
                            <input type="number" id="stores_limit" name="stores_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->stores_limit ?>" />
                            <small class="form-text text-muted"><?= $this->language->admin_plans->plan->stores_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="menus_limit"><?= $this->language->admin_plans->plan->menus_limit ?></label>
                            <input type="number" id="menus_limit" name="menus_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->menus_limit ?>" />
                            <small class="form-text text-muted"><?= $this->language->admin_plans->plan->menus_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="categories_limit"><?= $this->language->admin_plans->plan->categories_limit ?></label>
                            <input type="number" id="categories_limit" name="categories_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->categories_limit ?>" />
                            <small class="form-text text-muted"><?= $this->language->admin_plans->plan->categories_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="items_limit"><?= $this->language->admin_plans->plan->items_limit ?></label>
                            <input type="number" id="items_limit" name="items_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->items_limit ?>" />
                            <small class="form-text text-muted"><?= $this->language->admin_plans->plan->items_limit_help ?></small>
                        </div>

                        <div class="form-group">
                            <label for="domains_limit"><?= $this->language->admin_plans->plan->domains_limit ?></label>
                            <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->domains_limit ?>" />
                            <small class="form-text text-muted"><?= $this->language->admin_plans->plan->domains_limit_help ?></small>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="ordering_is_enabled" name="ordering_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->ordering_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="ordering_is_enabled"><?= $this->language->admin_plans->plan->ordering_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->ordering_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->additional_domains_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="additional_domains_is_enabled"><?= $this->language->admin_plans->plan->additional_domains_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->additional_domains_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->no_ads ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="no_ads"><?= $this->language->admin_plans->plan->no_ads ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->no_ads_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="analytics_is_enabled" name="analytics_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->analytics_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="analytics_is_enabled"><?= $this->language->admin_plans->plan->analytics_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->analytics_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="removable_branding_is_enabled" name="removable_branding_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->removable_branding_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="removable_branding_is_enabled"><?= $this->language->admin_plans->plan->removable_branding_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->removable_branding_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="custom_url_is_enabled" name="custom_url_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_url_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="custom_url_is_enabled"><?= $this->language->admin_plans->plan->custom_url_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_url_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="password_protection_is_enabled" name="password_protection_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->password_protection_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="password_protection_is_enabled"><?= $this->language->admin_plans->plan->password_protection_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->password_protection_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="search_engine_block_is_enabled" name="search_engine_block_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->search_engine_block_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="search_engine_block_is_enabled"><?= $this->language->admin_plans->plan->search_engine_block_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->search_engine_block_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="custom_css_is_enabled" name="custom_css_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_css_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="custom_css_is_enabled"><?= $this->language->admin_plans->plan->custom_css_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_css_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="custom_js_is_enabled" name="custom_js_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_js_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="custom_js_is_enabled"><?= $this->language->admin_plans->plan->custom_js_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_js_is_enabled_help ?></small></div>
                        </div>

                        <div class="custom-control custom-switch my-3">
                            <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->email_reports_is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="email_reports_is_enabled"><?= $this->language->admin_plans->plan->email_reports_is_enabled ?></label>
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->email_reports_is_enabled_help ?></small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_user_update->change_password->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_user_update->change_password->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->change_password->new_password ?></label>
                        <input type="password" name="new_password" class="form-control form-control-lg" />
                    </div>

                    <div class="form-group">
                        <label><?= $this->language->admin_user_update->change_password->repeat_password ?></label>
                        <input type="password" name="repeat_password" class="form-control form-control-lg" />
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 col-md-4"></div>

                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary"><?= $this->language->global->update ?></button>
                </div>
            </div>

        </form>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script>
    'use strict';

    let check_plan_id = () => {
        let selected_plan_id = $('[name="plan_id"]').find(':selected').attr('value');

        if(selected_plan_id == 'free') {
            $('#plan_expiration_date_container').hide();
        } else {
            $('#plan_expiration_date_container').show();
        }

        if(selected_plan_id == 'custom') {
            $('#plan_settings').show();
        } else {
            $('#plan_settings').hide();
        }
    };
    check_plan_id();

    /* Check for expiration date to show a warning if expired */
    let check_plan_expiration_date = () => {
        let plan_expiration_date = $('[name="plan_expiration_date"]');

        let plan_expiration_date_object = new Date(plan_expiration_date.val());
        let today_date_object = new Date();

        if(plan_expiration_date_object < today_date_object) {
            $(plan_expiration_date).addClass('is-invalid');
        } else {
            $(plan_expiration_date).removeClass('is-invalid');
        }
    };

    $('[name="plan_expiration_date"]').on('change', check_plan_expiration_date);
    check_plan_expiration_date();

    /* Daterangepicker */
    $('[name="plan_expiration_date"]').daterangepicker({
        startDate: <?= json_encode($data->user->plan_expiration_date) ?>,
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {
        check_plan_expiration_date()
    });

    /* Dont show expiration date when the chosen plan is the free one */
    $('[name="plan_id"]').on('change', check_plan_id);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
