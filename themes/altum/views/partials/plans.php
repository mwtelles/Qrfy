<?php defined('ALTUMCODE') || die() ?>

<?php

use Altum\Middlewares\Authentication;

?>

<?php if($this->settings->payment->is_enabled): ?>

    <?php
    $plans = [];
    $available_payment_frequencies = [];

    $plans_result = $this->database->query("SELECT * FROM `plans` WHERE `status` = 1");

    while($plan = $plans_result->fetch_object()) {
        $plans[] = $plan;

        foreach(['monthly', 'annual', 'lifetime'] as $value) {
            if($plan->{$value . '_price'}) {
                $available_payment_frequencies[$value] = true;
            }
        }
    }

    ?>

    <?php if(count($plans)): ?>
        <div class="mb-4 text-center">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">

                <?php if(isset($available_payment_frequencies['monthly'])): ?>
                <label class="btn btn-sm btn-outline-secondary active" data-payment-frequency="monthly">
                    <input type="radio" name="payment_frequency" checked> <?= $this->language->plan->custom_plan->monthly ?>
                </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['annual'])): ?>
                <label class="btn btn-sm btn-outline-secondary" data-payment-frequency="annual">
                    <input type="radio" name="payment_frequency"> <?= $this->language->plan->custom_plan->annual ?>
                </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['lifetime'])): ?>
                <label class="btn btn-sm btn-outline-secondary" data-payment-frequency="lifetime">
                    <input type="radio" name="payment_frequency"> <?= $this->language->plan->custom_plan->lifetime ?>
                </label>
                <?php endif ?>

            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<div class="row">
    <?php if($this->settings->plan_free->status == 1): ?>

        <div class="col-12 col-lg-4 mb-4">
            <div class="card pricing-card h-100">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3 text-center">
                        <span class="font-weight-bold text-black text-uppercase"><?= $this->settings->plan_free->name ?></span>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1">
                            <?= $this->language->plan->free->price ?>
                        </div>
                        <div>
                            <span class="text-muted"><?= $this->language->plan->free->help ?></span>
                        </div>
                    </div>

                    <div class="">
                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_free->settings->stores_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_stores_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->stores_limit, '<strong>' . nr($this->settings->plan_free->settings->stores_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->stores_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_free->settings->menus_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_menus_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->menus_limit, '<strong>' . nr($this->settings->plan_free->settings->menus_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->menus_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_free->settings->categories_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_categories_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->categories_limit, '<strong>' . nr($this->settings->plan_free->settings->categories_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->categories_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_free->settings->items_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_items_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->items_limit, '<strong>' . nr($this->settings->plan_free->settings->items_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->domains_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_free->settings->domains_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_domains_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->domains_limit, '<strong>' . nr($this->settings->plan_free->settings->domains_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>
                        <?php endif ?>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->ordering_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->ordering_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->ordering_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->additional_domains_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->additional_domains_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->additional_domains_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->additional_domains_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>
                        <?php endif ?>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->analytics_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->analytics_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->analytics_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->password_protection_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->password_protection_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->removable_branding_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->custom_url_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_url_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->custom_url_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->search_engine_block_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->search_engine_block_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->custom_css_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_css_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->custom_css_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->custom_js_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_js_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->custom_js_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->email_reports_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_free->settings->email_reports_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->email_reports_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_free->settings->email_reports_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>
                        <?php endif ?>
                    </div>
                </div>

                <div class="card-footer border-0">
                    <?php if(Authentication::check() && $this->user->plan_id == 'free'): ?>
                        <button class="btn btn-secondary btn-block"><?= $this->language->plan->button->already_free ?></button>
                    <?php else: ?>
                        <a href="<?= Authentication::check() ? url('pay/free') : url('register?redirect=pay/free') ?>" class="btn btn-primary btn-block"><?= $this->language->plan->button->choose ?></a>
                    <?php endif ?>
                </div>
            </div>
        </div>

    <?php endif ?>

    <?php if($this->settings->payment->is_enabled): ?>

        <?php if($this->settings->plan_trial->status == 1): ?>

        <div class="col-12 col-lg-4 mb-4">
            <div class="card pricing-card h-100">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3 text-center">
                        <span class="font-weight-bold text-black text-uppercase"><?= $this->settings->plan_trial->name ?></span>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1">
                            <?= $this->language->plan->trial->price ?>
                        </div>
                        <div>
                            <span class="text-muted"><?= $this->language->plan->trial->help ?></span>
                        </div>
                    </div>

                    <div class="">
                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_trial->settings->stores_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_stores_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->stores_limit, '<strong>' . nr($this->settings->plan_trial->settings->stores_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->stores_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_trial->settings->menus_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_menus_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->menus_limit, '<strong>' . nr($this->settings->plan_trial->settings->menus_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->menus_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_trial->settings->categories_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_categories_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->categories_limit, '<strong>' . nr($this->settings->plan_trial->settings->categories_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->categories_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_trial->settings->items_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_items_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->items_limit, '<strong>' . nr($this->settings->plan_trial->settings->items_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->domains_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($this->settings->plan_trial->settings->domains_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_domains_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->domains_limit, '<strong>' . nr($this->settings->plan_trial->settings->domains_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>
                        <?php endif ?>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->ordering_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->ordering_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->ordering_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->additional_domains_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->additional_domains_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->additional_domains_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->additional_domains_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>
                        <?php endif ?>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->analytics_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->analytics_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->analytics_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->password_protection_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->password_protection_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->removable_branding_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->custom_url_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_url_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->custom_url_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->search_engine_block_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->search_engine_block_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->custom_css_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_css_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->custom_css_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->custom_js_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_js_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->custom_js_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->email_reports_is_enabled): ?>
                            <div class="d-flex justify-content-between my-3 <?= $this->settings->plan_trial->settings->email_reports_is_enabled ? null : 'text-muted' ?>">
                                <div>
                                    <?= $this->language->global->plan_settings->email_reports_is_enabled ?>
                                </div>

                                <i class="fa fa-fw fa-sm <?= $this->settings->plan_trial->settings->email_reports_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

                <div class="card-footer border-0">
                    <?php if(Authentication::check() && $this->user->plan_id == 'trial'): ?>
                        <button class="btn btn-secondary btn-block"><?= $this->language->plan->button->already_trial ?></button>
                    <?php else: ?>
                        <a href="<?= Authentication::check() ? url('pay/trial') : url('register?redirect=pay/trial') ?>" class="btn btn-primary btn-block"><?= $this->language->plan->button->choose ?></a>
                    <?php endif ?>
                </div>
            </div>
        </div>

    <?php endif ?>

        <?php foreach($plans as $plan): ?>

        <?php $plan->settings = json_decode($plan->settings) ?>
        <?php $annual_price_savings = ceil(($plan->monthly_price * 12) - $plan->annual_price); ?>

        <div
            class="col-12 col-lg-4 mb-4"
            data-plan-monthly="<?= json_encode((bool) $plan->monthly_price) ?>"
            data-plan-annual="<?= json_encode((bool) $plan->annual_price) ?>"
            data-plan-lifetime="<?= json_encode((bool) $plan->lifetime_price) ?>"
        >
            <div class="card pricing-card h-100">
                <div class="card-body d-flex flex-column">

                    <div class="mb-3 text-center">
                        <span class="font-weight-bold text-black text-uppercase"><?= $plan->name ?></span>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="h1 d-none" data-plan-payment-frequency="monthly">
                            <?= $plan->monthly_price ?>
                        </div>
                        <div class="h1 d-none" data-plan-payment-frequency="annual">
                            <?= $plan->annual_price ?>
                        </div>
                        <div class="h1 d-none" data-plan-payment-frequency="lifetime">
                            <?= $plan->lifetime_price ?>
                        </div>
                        <span class="h5 text-muted">
                            <?= $this->settings->payment->currency ?>
                        </span>

                        <div>
                            <span class="text-muted d-none" data-plan-payment-frequency="monthly">
                                <?= $this->language->plan->custom_plan->monthly_payments ?>
                            </span>
                            <span class="text-muted d-none" data-plan-payment-frequency="annual">
                                <?= $this->language->plan->custom_plan->annual_payments ?>
                                <?php if($plan->monthly_price): ?>
                                <span><?= sprintf($this->language->plan->custom_plan->annual_savings, '<span class="badge badge-success">-' . $annual_price_savings, $this->settings->payment->currency . '</span>') ?></span>
                                <?php endif ?>
                            </span>
                            <span class="text-muted d-none" data-plan-payment-frequency="lifetime">
                                <?= $this->language->plan->custom_plan->lifetime_payments ?>
                            </span>
                        </div>
                    </div>

                    <div class="">
                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($plan->settings->stores_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_stores_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->stores_limit, '<strong>' . nr($plan->settings->stores_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->stores_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($plan->settings->menus_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_menus_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->menus_limit, '<strong>' . nr($plan->settings->menus_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->menus_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($plan->settings->categories_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_categories_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->categories_limit, '<strong>' . nr($plan->settings->categories_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->categories_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($plan->settings->items_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_items_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->items_limit, '<strong>' . nr($plan->settings->items_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->domains_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <?php if($plan->settings->domains_limit == -1): ?>
                                    <?= $this->language->global->plan_settings->unlimited_domains_limit ?>
                                <?php else: ?>
                                    <?= sprintf($this->language->global->plan_settings->domains_limit, '<strong>' . nr($plan->settings->domains_limit) . '</strong>') ?>
                                <?php endif ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->items_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                        </div>
                        <?php endif ?>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->ordering_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->ordering_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->ordering_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->additional_domains_is_enabled): ?>
                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->additional_domains_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->additional_domains_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->additional_domains_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>
                        <?php endif ?>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->analytics_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->analytics_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->analytics_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->password_protection_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->password_protection_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->removable_branding_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->custom_url_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_url_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->custom_url_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->search_engine_block_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->search_engine_block_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->custom_css_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_css_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->custom_css_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <div class="d-flex justify-content-between my-3 <?= $plan->settings->custom_js_is_enabled ? null : 'text-muted' ?>">
                            <div>
                                <?= $this->language->global->plan_settings->custom_js_is_enabled ?>
                            </div>

                            <i class="fa fa-fw fa-sm <?= $plan->settings->custom_js_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                        </div>

                        <?php if($this->settings->stores->email_reports_is_enabled): ?>
                            <div class="d-flex justify-content-between my-3 <?= $plan->settings->email_reports_is_enabled ? null : 'text-muted' ?>">
                                <div>
                                    <?= $this->language->global->plan_settings->email_reports_is_enabled ?>
                                </div>

                                <i class="fa fa-fw fa-sm <?= $plan->settings->email_reports_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

                <div class="card-footer border-0">
                    <a href="<?= Authentication::check() ? url('pay/' . $plan->plan_id) : url('register?redirect=pay/' . $plan->plan_id) ?>" class="btn btn-primary btn-block"><?= $this->language->plan->button->choose ?></a>
                </div>
            </div>
        </div>

    <?php endforeach ?>

        <?php ob_start() ?>
            <script>
                'use strict';

                let payment_frequency_handler = (event = null) => {

                    let payment_frequency = null;

                    if(event) {
                        payment_frequency = $(event.currentTarget).data('payment-frequency');
                    } else {
                        payment_frequency = $('[name="payment_frequency"]:checked').closest('label').data('payment-frequency');
                    }

                    switch(payment_frequency) {
                        case 'monthly':
                            $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');
                            $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                            break;

                        case 'annual':
                            $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                            $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                            break

                        case 'lifetime':
                            $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                            $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');

                            break
                    }

                    $(`[data-plan-payment-frequency="${payment_frequency}"]`).addClass('d-inline-block');

                    $(`[data-plan-${payment_frequency}="true"]`).removeClass('d-none').addClass('d-inline-block');
                    $(`[data-plan-${payment_frequency}="false"]`).addClass('d-none').removeClass('d-inline-block');

                };

                $('[data-payment-frequency]').on('click', payment_frequency_handler);

                payment_frequency_handler();
            </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php endif ?>
</div>


