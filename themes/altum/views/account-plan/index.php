<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php display_notifications() ?>

    <div class="">
        <div class="row mb-3">
            <div class="col-12 col-xl">
                <h1 class="h4"><?= $this->user->plan->name ?></h1>
                <?php if($this->user->plan_id != 'free'): ?>
                    <p class="text-muted">
                        <?= sprintf(
                            $this->user->payment_subscription_id ? $this->language->account_plan->plan->renews : $this->language->account_plan->plan->expires,
                            '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>'
                        ) ?>
                    </p>
                <?php endif ?>
            </div>

            <?php if($this->settings->payment->is_enabled): ?>
                <div class="col-12 col-xl-auto">
                    <?php if($this->user->plan_id == 'free'): ?>
                        <a href="<?= url('plan/upgrade') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-arrow-up"></i> <?= $this->language->account->plan->upgrade_plan ?></a>
                    <?php elseif($this->user->plan_id == 'trial'): ?>
                        <a href="<?= url('plan/renew') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-sync-alt"></i> <?= $this->language->account->plan->renew_plan ?></a>
                    <?php else: ?>
                        <a href="<?= url('plan/renew') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-sync-alt"></i> <?= $this->language->account->plan->renew_plan ?></a>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>

        <?= (new \Altum\Views\View('partials/plan_features', ['settings' => $this->settings]))->run(['plan_settings' => $this->user->plan_settings]) ?>
    </div>

    <?php if($this->user->plan_id != 'free' && $this->user->payment_subscription_id): ?>
        <hr class="border-gray-50 my-4" />

        <div class="">
            <h1 class="h4"><?= $this->language->account_plan->cancel->header ?></h1>
            <p class="text-muted"><?= $this->language->account_plan->cancel->subheader ?></p>

            <a href="<?= url('account/cancelsubscription' . \Altum\Middlewares\Csrf::get_url_query()) ?>" class="btn btn-block btn-outline-secondary" data-confirm="<?= $this->language->account_plan->cancel->confirm_message ?>"><?= $this->language->account_plan->cancel->cancel ?></a>
        </div>
    <?php endif ?>

    <?php if($this->settings->payment->is_enabled && $this->settings->payment->codes_is_enabled): ?>
        <hr class="border-gray-50 my-4" />

        <div class="">

            <h2 class="h4"><?= $this->language->account_plan->code->header ?></h2>
            <p class="text-muted"><?= $this->language->account_plan->code->subheader ?></p>

            <form id="code" action="<?= url('account-plan/redeem_code') ?>" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label><i class="fa fa-fw fa-sm fa-tags text-muted mr-1"></i> <?= $this->language->account_plan->code->input ?></label>
                    <input type="text" name="code" class="form-control" />
                    <div class="mt-2"><span id="code_help" class="text-muted"></span></div>
                </div>

                <button id="code_submit" type="submit" name="submit" class="btn btn-primary" style="display: none;"><?= $this->language->account_plan->code->submit ?></button>
            </form>

        </div>

        <?php ob_start() ?>
        <script>
            'use strict';

            /* Disable form submission for code form */
            $('#code').on('submit', event => {
                let code = $('input[name="code"]').val();

                if(code.trim() == '') {
                    event.preventDefault();
                }
            });

            let timer = null;

            $('input[name="code"]').on('change paste keyup', event => {

                let code = $(event.currentTarget).val();
                let is_valid = false;

                clearTimeout(timer);

                if(code.trim() == '') {
                    $('#code_help').html('');
                    $(event.currentTarget).removeClass('is-invalid').removeClass('is-valid');
                    $('#code_submit').hide();

                    return;
                }

                timer = setTimeout(() => {
                    $.ajax({
                        type: 'POST',
                        url: `${url}account-plan/code`,
                        data: {code, global_token},
                        success: data => {

                            if(data.status == 'success') {
                                is_valid = true;
                            }

                            $('#code_help').html(data.message);

                            if(is_valid) {
                                $(event.currentTarget).addClass('is-valid');
                                $(event.currentTarget).removeClass('is-invalid');
                                $('#code_submit').show();
                            } else {
                                $(event.currentTarget).addClass('is-invalid');
                                $(event.currentTarget).removeClass('is-valid');
                                $('#code_submit').hide();
                            }

                        },
                        dataType: 'json'
                    });
                }, 500);

            });
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
    <?php endif ?>

</div>

