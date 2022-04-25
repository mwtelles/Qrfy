<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php display_notifications() ?>

    <h1 class="h4"><?= $this->language->account_api->header ?></h1>
    <p class="text-muted"><?= sprintf($this->language->account_api->subheader, '<a href="' . url('api-documentation') . '">', '</a>') ?></p>

    <form action="" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <label for="api_key"><?= $this->language->account_api->api_key ?></label>
        <div class="input-group mb-3">
            <input type="text" id="api_key" name="api_key" value="<?= $this->user->api_key ?>" class="form-control" readonly="readonly" />

            <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-block btn-outline-secondary"><?= $this->language->account_api->button ?></button>
            </div>
        </div>

    </form>

</div>
