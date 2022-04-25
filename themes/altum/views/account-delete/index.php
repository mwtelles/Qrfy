<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php display_notifications() ?>

    <h1 class="h4"><?= $this->language->account_delete->header ?></h1>
    <p class="text-muted"><?= $this->language->account_delete->subheader ?></p>

    <form action="" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <div class="form-group">
            <label for="current_password"><?= $this->language->account_delete->current_password ?></label>
            <input type="password" id="current_password" name="current_password" class="form-control" />
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-danger"><?= $this->language->global->delete ?></button>
    </form>

</div>
