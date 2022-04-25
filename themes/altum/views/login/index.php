<?php defined('ALTUMCODE') || die() ?>

<?php display_notifications() ?>

<h1 class="h5"><?= sprintf($this->language->login->header, $this->settings->title) ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <div class="form-group">
        <label><?= $this->language->login->form->email ?></label>
        <input type="text" name="email" class="form-control" value="<?= $data->values['email'] ?>" required="required" />
    </div>

    <div class="form-group">
        <label><?= $this->language->login->form->password ?></label>
        <input type="password" name="password" class="form-control" <?= $data->login_account ? 'value="' . $data->values['password'] . '"' : null ?> required="required" />
    </div>

    <?php if($data->login_account && $data->login_account->twofa_secret && $data->login_account->active): ?>
        <div class="form-group">
            <label><?= $this->language->login->form->twofa_token ?></label>
            <input type="text" name="twofa_token" class="form-control" required="required" autocomplete="off" />
        </div>
    <?php endif ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="rememberme">
                <small class="form-text text-muted"><?= $this->language->login->form->remember_me ?></small>
            </label>
        </div>

        <small><a href="lost-password" class="text-muted"><?= $this->language->login->display->lost_password ?></a> / <a href="resend-activation" class="text-muted" role="button"><?= $this->language->login->display->resend_activation ?></a></small>
    </div>


    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= $this->language->login->form->login ?></button>
    </div>

    <div class="row">
        <?php if($this->settings->facebook->is_enabled): ?>
            <div class="col-sm mt-1">
                <a href="<?= $data->facebook_login_url ?>" class="btn btn-light btn-block"><?= sprintf($this->language->login->display->facebook, "<i class=\"fab fa-fw fa-facebook\"></i>") ?></a>
            </div>
        <?php endif ?>
    </div>

</form>


<?php if($this->settings->register_is_enabled): ?>
    <div class="mt-5 text-center text-muted">
        <?= sprintf($this->language->login->display->register, '<a href="' . url('register') . '" class="font-weight-bold">' . $this->language->login->display->register_help . '</a>') ?></a>
    </div>
<?php endif ?>

