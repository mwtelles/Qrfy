<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mr-3"><i class="fa fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= sprintf($this->language->admin_plan_update->header, $data->plan->name) ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/partials/admin_plan_dropdown_button.php', ['id' => $data->plan->plan_id]) ?>
    </div>
</div>

<?php display_notifications() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />
            <input type="hidden" name="type" value="update" />

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_plans->main->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_plans->main->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="name"><?= $this->language->admin_plans->main->name ?></label>
                        <input type="text" id="name" name="name" class="form-control form-control-lg" value="<?= $data->plan->name ?>" />
                    </div>

                    <div class="form-group">
                        <label for="status"><?= $this->language->admin_plans->main->status ?></label>
                        <select id="status" name="status" class="form-control form-control-lg">
                            <option value="1" <?= $data->plan->status == 1 ? 'selected="selected"' : null ?>><?= $this->language->global->active ?></option>
                            <option value="0" <?= $data->plan->status == 0 ? 'selected="selected"' : null ?>><?= $this->language->global->disabled ?></option>
                            <option value="2" <?= $data->plan->status == 2 ? 'selected="selected"' : null ?>><?= $this->language->global->hidden ?></option>
                        </select>
                    </div>

                    <?php if($data->plan_id == 'trial'): ?>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="days"><?= $this->language->admin_plans->main->trial->days ?></label>
                            <input type="text" id="days" name="days" class="form-control form-control-lg" value="<?= $data->plan->days ?>" />
                            <div><small class="form-text text-muted"><?= $this->language->admin_plans->main->trial->days_help ?></small></div>
                        </div>
                    </div>
                    <?php endif ?>

                    <?php if(is_numeric($data->plan_id)): ?>
                        <div class="row">
                            <div class="col-sm-12 col-xl-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="monthly_price"><?= $this->language->admin_plans->main->monthly_price ?> <small class="form-text text-muted"><?= $this->settings->payment->currency ?></small></label>
                                        <input type="text" id="monthly_price" name="monthly_price" class="form-control form-control-lg" value="<?= $data->plan->monthly_price ?>" />
                                        <small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->price_help, $this->language->admin_plans->main->monthly_price) ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-xl-4">
                                <div class="form-group">
                                    <label for="annual_price"><?= $this->language->admin_plans->main->annual_price ?> <small class="form-text text-muted"><?= $this->settings->payment->currency ?></small></label>
                                    <input type="text" id="annual_price" name="annual_price" class="form-control form-control-lg" value="<?= $data->plan->annual_price ?>" />
                                    <small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->price_help, $this->language->admin_plans->main->annual_price) ?></small>
                                </div>
                            </div>

                            <div class="col-sm-12 col-xl-4">
                                <div class="form-group">
                                    <label for="lifetime_price"><?= $this->language->admin_plans->main->lifetime_price ?> <small class="form-text text-muted"><?= $this->settings->payment->currency ?></small></label>
                                    <input type="text" id="lifetime_price" name="lifetime_price" class="form-control form-control-lg" value="<?= $data->plan->lifetime_price ?>" />
                                    <small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->price_help, $this->language->admin_plans->main->lifetime_price) ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <span><?= $this->language->admin_plans->main->taxes_ids ?></span>
                            <div><small class="form-text text-muted"><?= sprintf($this->language->admin_plans->main->taxes_ids_help, '<a href="' . url('admin/taxes') .'">', '</a>') ?></small></div>
                        </div>

                        <?php if($data->taxes): ?>
                            <div class="row">
                                <?php foreach($data->taxes as $row): ?>
                                    <div class="col-12 col-xl-6">
                                        <div class="custom-control custom-switch my-3">
                                            <input id="<?= 'tax_id_' . $row->tax_id ?>" name="taxes_ids[<?= $row->tax_id ?>]" type="checkbox" class="custom-control-input" <?= in_array($row->tax_id, $data->plan->taxes_ids) ? 'checked="checked"' : null ?>>
                                            <label class="custom-control-label" for="<?= 'tax_id_' . $row->tax_id ?>"><?= $row->internal_name ?></label>
                                            <div><small><?= $row->name ?></small> - <small class="form-text text-muted"><?= $row->description ?></small></div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>

                    <?php endif ?>

                </div>
            </div>

            <div class="mt-5"></div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="h4"><?= $this->language->admin_plans->plan->header ?></h2>
                    <p class="text-muted"><?= $this->language->admin_plans->plan->subheader ?></p>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="stores_limit"><?= $this->language->admin_plans->plan->stores_limit ?></label>
                        <input type="number" id="stores_limit" name="stores_limit" min="-1" class="form-control form-control-lg" value="<?= $data->plan->settings->stores_limit ?>" <?= $data->plan_id == 'guest' ? 'readonly="readonly"' : 'required="required"' ?> />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->stores_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="menus_limit"><?= $this->language->admin_plans->plan->menus_limit ?></label>
                        <input type="number" id="menus_limit" name="menus_limit" min="-1" class="form-control form-control-lg" value="<?= $data->plan->settings->menus_limit ?>" <?= $data->plan_id == 'guest' ? 'readonly="readonly"' : 'required="required"' ?> />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->menus_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="categories_limit"><?= $this->language->admin_plans->plan->categories_limit ?></label>
                        <input type="number" id="categories_limit" name="categories_limit" min="-1" class="form-control form-control-lg" value="<?= $data->plan->settings->categories_limit ?>" <?= $data->plan_id == 'guest' ? 'readonly="readonly"' : 'required="required"' ?> />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->categories_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="items_limit"><?= $this->language->admin_plans->plan->items_limit ?></label>
                        <input type="number" id="items_limit" name="items_limit" min="-1" class="form-control form-control-lg" value="<?= $data->plan->settings->items_limit ?>" <?= $data->plan_id == 'guest' ? 'readonly="readonly"' : 'required="required"' ?> />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->items_limit_help ?></small>
                    </div>

                    <div class="form-group">
                        <label for="domains_limit"><?= $this->language->admin_plans->plan->domains_limit ?></label>
                        <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control form-control-lg" value="<?= $data->plan->settings->domains_limit ?>" <?= $data->plan_id == 'guest' ? 'readonly="readonly"' : 'required="required"' ?> />
                        <small class="form-text text-muted"><?= $this->language->admin_plans->plan->domains_limit_help ?></small>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="ordering_is_enabled" name="ordering_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->ordering_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="ordering_is_enabled"><?= $this->language->admin_plans->plan->ordering_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->ordering_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->additional_domains_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="additional_domains_is_enabled"><?= $this->language->admin_plans->plan->additional_domains_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->additional_domains_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->plan->settings->no_ads ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="no_ads"><?= $this->language->admin_plans->plan->no_ads ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->no_ads_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="analytics_is_enabled" name="analytics_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->analytics_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="analytics_is_enabled"><?= $this->language->admin_plans->plan->analytics_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->analytics_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="removable_branding_is_enabled" name="removable_branding_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->removable_branding_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="removable_branding_is_enabled"><?= $this->language->admin_plans->plan->removable_branding_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->removable_branding_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="custom_url_is_enabled" name="custom_url_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_url_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="custom_url_is_enabled"><?= $this->language->admin_plans->plan->custom_url_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_url_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="password_protection_is_enabled" name="password_protection_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->password_protection_is_enabled ? 'checked="checked"' : null ?> <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?>>
                        <label class="custom-control-label" for="password_protection_is_enabled"><?= $this->language->admin_plans->plan->password_protection_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->password_protection_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="search_engine_block_is_enabled" name="search_engine_block_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->search_engine_block_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="search_engine_block_is_enabled"><?= $this->language->admin_plans->plan->search_engine_block_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->search_engine_block_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="custom_css_is_enabled" name="custom_css_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_css_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="custom_css_is_enabled"><?= $this->language->admin_plans->plan->custom_css_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_css_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="custom_js_is_enabled" name="custom_js_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_js_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="custom_js_is_enabled"><?= $this->language->admin_plans->plan->custom_js_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->custom_js_is_enabled_help ?></small></div>
                    </div>

                    <div class="custom-control custom-switch my-3">
                        <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->email_reports_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="email_reports_is_enabled"><?= $this->language->admin_plans->plan->email_reports_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= $this->language->admin_plans->plan->email_reports_is_enabled_help ?></small></div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 col-md-4"></div>

                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary mb-1"><?= $this->language->global->update ?></button>
                    <button type="submit" name="submit_update_users_plan_settings" class="btn btn-outline-primary mb-1"><?= $this->language->admin_plan_update->update_users_plan_settings->button ?></button>
                </div>
            </div>
        </form>

    </div>
</div>

