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
                <li class="active" aria-current="page"><?= $this->language->store_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->store_update->header, $data->store->name) ?></h1>
    <p>
        <a href="<?= url('s/' . $data->store->url) ?>" target="_blank">
            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->store->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

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

    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="main-tab" data-toggle="pill" href="#main" role="tab" aria-controls="main" aria-selected="true">
                <i class="fa fa-fw fa-sm fa-cogs mr-1"></i> <?= $this->language->store_update->main ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ordering-tab" data-toggle="pill" href="#ordering" role="tab" aria-controls="ordering" aria-selected="false">
                <i class="fa fa-fw fa-sm fa-bell mr-1"></i> <?= $this->language->store_update->ordering ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="extra-tab" data-toggle="pill" href="#extra" role="tab" aria-controls="extra" aria-selected="false">
                <i class="fa fa-fw fa-sm fa-sliders-h mr-1"></i> <?= $this->language->store_update->extra ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="visuals-tab" data-toggle="pill" href="#visuals" role="tab" aria-controls="visuals" aria-selected="false">
                <i class="fa fa-fw fa-sm fa-paint-brush mr-1"></i> <?= $this->language->store_update->visuals ?>
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="socials-tab" data-toggle="pill" href="#socials" role="tab" aria-controls="socials" aria-selected="false">
                <i class="fa fa-fw fa-sm fa-share-alt mr-1"></i> <?= $this->language->store_update->socials ?>
            </a>
        </li>
    </ul>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <div class="tab-content">

            <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">

                <?php if(count($data->domains) && ($this->settings->stores->domains_is_enabled || $this->settings->stores->additional_domains_is_enabled)): ?>
                    <div class="form-group">
                        <label for="domain_id"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= $this->language->store->input->domain_id ?></label>
                        <select id="domain_id" name="domain_id" class="form-control">
                            <?php if($this->settings->stores->main_domain_is_enabled || \Altum\Middlewares\Authentication::is_admin()): ?>
                                <option value="" <?= $data->store->domain_id ? null : 'selected="selected"' ?>><?= url('s/') ?></option>
                            <?php endif ?>

                            <?php foreach($data->domains as $row): ?>
                                <option value="<?= $row->domain_id ?>" data-type="<?= $row->type ?>" <?= $data->store->domain_id && $data->store->domain_id == $row->domain_id ? 'selected="selected"' : null ?>><?= $row->url ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= $this->language->store->input->domain_id_help ?></small>
                    </div>

                    <div id="is_main_store_wrapper" class="custom-control custom-switch my-3">
                        <input id="is_main_store" name="is_main_store" type="checkbox" class="custom-control-input" <?= $data->store->domain_id && $data->domains[$data->store->domain_id]->store_id == $data->store->store_id ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="is_main_store"><?= $this->language->store->input->is_main_store ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->is_main_store_help ?></small>
                    </div>

                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="form-group">
                                <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= $this->language->store->input->url ?></label>
                                <input type="text" id="url" name="url" class="form-control" value="<?= $data->store->url ?>" placeholder="<?= $this->language->store->input->url_placeholder ?>" />
                                <small class="form-text text-muted"><?= $this->language->store->input->url_help ?></small>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div <?= $this->user->plan_settings->custom_url_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                        <div class="<?= $this->user->plan_settings->custom_url_is_enabled ? null : 'container-disabled' ?>">
                            <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= $this->language->store->input->url ?></label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?= url('s/') ?></span>
                                    </div>
                                    <input type="text" id="url" name="url" class="form-control" value="<?= $data->store->url ?>" placeholder="<?= $this->language->store->input->url_placeholder ?>" />
                                </div>
                                <small class="form-text text-muted"><?= $this->language->store->input->url_help ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->store->input->name ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $data->store->name ?>" placeholder="<?= $this->language->store->input->name_placeholder ?>" required="required" />
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="title"><i class="fa fa-fw fa-sm fa-heading text-muted mr-1"></i> <?= $this->language->store->input->title ?></label>
                            <input type="text" id="title" name="title" class="form-control" value="<?= $data->store->title ?>" required="required" />
                            <small class="form-text text-muted"><?= $this->language->store->input->title_help ?></small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= $this->language->store->input->description ?></label>
                            <input type="text" id="description" name="description" class="form-control" value="<?= $data->store->description ?>" />
                            <small class="form-text text-muted"><?= $this->language->store->input->description_help ?></small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address"><i class="fa fa-fw fa-sm fa-map-pin text-muted mr-1"></i> <?= $this->language->store->input->address ?></label>
                    <input type="text" id="address" name="address" class="form-control" value="<?= $data->store->details->address ?>" />
                    <small class="form-text text-muted"><?= $this->language->store->input->address_help ?></small>
                </div>

                <div class="form-group">
                    <label for="currency"><i class="fa fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= $this->language->store->input->currency ?></label>
                    <input type="text" id="currency" name="currency" class="form-control" value="<?= $data->store->currency ?>" required="required" />
                    <small class="form-text text-muted"><?= $this->language->store->input->currency_help ?></small>
                </div>

                <div class="form-group">
                    <label for="timezone"><i class="fa fa-fw fa-sm fa-clock text-muted mr-1"></i> <?= $this->language->store->input->timezone ?></label>
                    <select id="timezone" name="timezone" class="form-control">
                        <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . ($data->store->timezone == $timezone ? 'selected="selected"' : null) . '>' . $timezone . '</option>' ?>
                    </select>
                    <small class="form-text text-muted"><?= $this->language->store->input->timezone_help ?></small>
                </div>


                <div class="<?= $this->settings->stores->email_reports_is_enabled ? null : 'd-none' ?>" <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'container-disabled' ?>">
                        <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input" <?= $data->store->email_reports_is_enabled ? 'checked="checked"' : null?> <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'disabled="disabled"' ?>>
                        <label class="custom-control-label" for="email_reports_is_enabled"><?= $this->language->store->input->email_reports_is_enabled ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->email_reports_is_enabled_help ?></small>
                    </div>
                </div>

                <div class="custom-control custom-switch my-3">
                    <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->store->is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="is_enabled"><?= $this->language->store->input->is_enabled ?></label>
                    <small class="form-text text-muted"><?= $this->language->store->input->is_enabled_help ?></small>
                </div>
            </div>

            <div class="tab-pane fade" id="ordering" role="tabpanel" aria-labelledby="ordering-tab">
                <div <?= $this->user->plan_settings->ordering_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->ordering_is_enabled ? null : 'container-disabled' ?>">
                        <input id="on_premise_ordering_is_enabled" name="on_premise_ordering_is_enabled" type="checkbox" class="custom-control-input" <?= $data->store->on_premise_ordering_is_enabled ? 'checked="checked"' : null?> <?= $this->user->plan_settings->ordering_is_enabled ? null : 'disabled="disabled"' ?>>
                        <label class="custom-control-label" for="on_premise_ordering_is_enabled"><?= $this->language->store->input->on_premise_ordering_is_enabled ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->on_premise_ordering_is_enabled_help ?></small>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->ordering_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->ordering_is_enabled ? null : 'container-disabled' ?>">
                        <input id="takeaway_ordering_is_enabled" name="takeaway_ordering_is_enabled" type="checkbox" class="custom-control-input" <?= $data->store->takeaway_ordering_is_enabled ? 'checked="checked"' : null?> <?= $this->user->plan_settings->ordering_is_enabled ? null : 'disabled="disabled"' ?>>
                        <label class="custom-control-label" for="takeaway_ordering_is_enabled"><?= $this->language->store->input->takeaway_ordering_is_enabled ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->takeaway_ordering_is_enabled_help ?></small>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->ordering_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->ordering_is_enabled ? null : 'container-disabled' ?>">
                        <input id="delivery_ordering_is_enabled" name="delivery_ordering_is_enabled" type="checkbox" class="custom-control-input" <?= $data->store->delivery_ordering_is_enabled ? 'checked="checked"' : null?> <?= $this->user->plan_settings->ordering_is_enabled ? null : 'disabled="disabled"' ?>>
                        <label class="custom-control-label" for="delivery_ordering_is_enabled"><?= $this->language->store->input->delivery_ordering_is_enabled ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->delivery_ordering_is_enabled_help ?></small>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="extra" role="tabpanel" aria-labelledby="extra-tab">
                <div class="form-group">
                    <label for="phone"><i class="fa fa-fw fa-sm fa-phone text-muted mr-1"></i> <?= $this->language->store->input->phone ?></label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?= $data->store->details->phone ?>" placeholder="<?= $this->language->store->input->phone_placeholder ?>" />
                </div>

                <div class="form-group">
                    <label for="website"><i class="fa fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= $this->language->store->input->website ?></label>
                    <input type="text" id="website" name="website" class="form-control" value="<?= $data->store->details->website ?>" placeholder="<?= $this->language->store->input->website_placeholder ?>" />
                </div>

                <div class="form-group">
                    <label for="email"><i class="fa fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= $this->language->store->input->email ?></label>
                    <input type="text" id="email" name="email" class="form-control" value="<?= $data->store->details->email ?>" placeholder="<?= $this->language->store->input->email_placeholder ?>" />
                </div>

                <div <?= $this->user->plan_settings->password_protection_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->password_protection_is_enabled ? null : 'container-disabled' ?>">
                        <label for="password"><i class="fa fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= $this->language->store->input->password ?></label>
                        <input type="password" id="password" name="password" class="form-control" value="<?= $data->store->password ?>" autocomplete="off" />
                    </div>
                </div>

                <div <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'container-disabled' ?>">
                        <input id="is_se_visible" name="is_se_visible" type="checkbox" class="custom-control-input" <?= $data->store->is_se_visible ? 'checked="checked"' : null?> <?= $this->user->plan_settings->search_engine_block_is_enabled ? null : 'disabled="disabled"' ?>>
                        <label class="custom-control-label" for="is_se_visible"><?= $this->language->store->input->is_se_visible ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->is_se_visible_help ?></small>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="custom-control custom-switch my-3 <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'container-disabled' ?>">
                        <input id="is_removed_branding" name="is_removed_branding" type="checkbox" class="custom-control-input" <?= $data->store->is_removed_branding ? 'checked="checked"' : null?> <?= $this->user->plan_settings->removable_branding_is_enabled ? null : 'disabled="disabled"' ?>>
                        <label class="custom-control-label" for="is_removed_branding"><?= $this->language->store->input->is_removed_branding ?></label>
                        <small class="form-text text-muted"><?= $this->language->store->input->is_removed_branding_help ?></small>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label><i class="fa fa-fw fa-sm fa-hourglass-half text-muted mr-1"></i> <?= $this->language->store->input->hours ?></label><br />
                        <small class="form-text text-muted"><?= $this->language->store->input->hours_help ?></small>
                    </div>

                    <?php foreach(['1', '2', '3', '4', '5', '6', '7'] as $day): ?>
                        <div class="mb-3">
                            <div class="custom-control custom-switch mb-1">
                                <input id="hours_<?= $day ?>_is_enabled" name="hours[<?= $day ?>][is_enabled]" type="checkbox" class="custom-control-input" <?= $data->store->details->hours->{$day}->is_enabled ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="hours_<?= $day ?>_is_enabled"><?= $this->language->global->date->long_days->{$day} ?></label>
                            </div>

                            <div class="form-group">
                                <input type="text" id="hours_<?= $day ?>_start" name="hours[<?= $day ?>][hours]" class="form-control" value="<?= $data->store->details->hours->{$day}->hours ?>" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="tab-pane fade" id="visuals" role="tabpanel" aria-labelledby="visuals-tab">
                <div class="form-group">
                    <label for="logo"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= $this->language->store->input->logo ?></label>
                    <?php if(!empty($data->store->logo)): ?>
                        <div class="m-1 col-3">
                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'store_logos/' . $data->store->logo ?>" class="img-fluid" loading="lazy" />
                        </div>
                    <?php endif ?>
                    <input id="logo" type="file" name="logo" accept=".gif, .png, .jpg, .jpeg, .svg" class="form-control-file" />
                    <small class="form-text text-muted"><?= $this->language->store->input->logo_help ?></small>
                </div>

                <div class="form-group">
                    <label for="favicon"><i class="fa fa-fw fa-sm fa-clone text-muted mr-1"></i> <?= $this->language->store->input->favicon ?></label>
                    <?php if(!empty($data->store->favicon)): ?>
                        <div class="m-1 col-3">
                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'store_favicons/' . $data->store->favicon ?>" class="img-fluid" loading="lazy" />
                        </div>
                    <?php endif ?>
                    <input id="favicon" type="file" name="favicon" accept=".gif, .png, .jpg, .jpeg, .ico" class="form-control-file" />
                    <small class="form-text text-muted"><?= $this->language->store->input->favicon_help ?></small>
                </div>

                <div class="form-group">
                    <label for="image"><i class="fa fa-fw fa-sm fa-clone text-muted mr-1"></i> <?= $this->language->store->input->image ?></label>
                    <?php if(!empty($data->store->image)): ?>
                        <div class="m-1 col-3">
                            <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'store_images/' . $data->store->image ?>" class="img-fluid" loading="lazy" />
                        </div>
                    <?php endif ?>
                    <input id="image" type="file" name="image" accept=".gif, .png, .jpg, .jpeg, .svg" class="form-control-file" />
                    <small class="form-text text-muted"><?= $this->language->store->input->image_help ?></small>
                </div>

                <div <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'container-disabled' ?>">
                        <label for="custom_css"><i class="fa fa-fw fa-sm fa-code text-muted mr-1"></i> <?= $this->language->store->input->custom_css ?></label>
                        <textarea id="custom_css" class="form-control" name="custom_css"><?= $data->store->custom_css ?></textarea>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'container-disabled' ?>">
                        <label for="custom_js"><i class="fa fa-fw fa-sm fa-code text-muted mr-1"></i> <?= $this->language->store->input->custom_js ?></label>
                        <textarea id="custom_js" class="form-control" name="custom_js"><?= $data->store->custom_js ?></textarea>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="socials" role="tabpanel" aria-labelledby="socials-tab">
                <?php foreach(require APP_PATH . 'includes/s/socials.php' as $key => $value): ?>

                    <div class="form-group">
                        <label><i class="fab fa-<?= $key ?> fa-fw fa-sm mr-1"></i> <?= $this->language->store->input->{$key} ?></label>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?= str_replace('%s', '', $value['format']) ?></span>
                            </div>
                            <input type="text" class="form-control" name="socials[<?= $key ?>]" placeholder="<?= $this->language->store->input->{$key . '_placeholder'} ?>" value="<?= $data->store->socials->{$key} ?? '' ?>" />
                        </div>
                    </div>

                <?php endforeach ?>
            </div>

        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->update ?></button>
    </form>

</div>


<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Is main store handler */
    let is_main_store_handler = () => {
        if(document.querySelector('#is_main_store').checked) {
            document.querySelector('#url').setAttribute('disabled', 'disabled');
        } else {
            document.querySelector('#url').removeAttribute('disabled');
        }
    }

    document.querySelector('#is_main_store') && document.querySelector('#is_main_store').addEventListener('change', is_main_store_handler);

    /* Domain Id Handler */
    let domain_id_handler = () => {
        let domain_id = document.querySelector('select[name="domain_id"]').value;

        if(document.querySelector(`select[name="domain_id"] option[value="${domain_id}"]`).getAttribute('data-type') == '0') {
            document.querySelector('#is_main_store_wrapper').classList.remove('d-none');
        } else {
            document.querySelector('#is_main_store_wrapper').classList.add('d-none');
            document.querySelector('#is_main_store').checked = false;
        }

        is_main_store_handler();
    }

    domain_id_handler();

    document.querySelector('select[name="domain_id"]') && document.querySelector('select[name="domain_id"]').addEventListener('change', domain_id_handler);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

