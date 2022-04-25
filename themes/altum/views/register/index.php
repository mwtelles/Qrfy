<?php defined('ALTUMCODE') || die() ?>

<?php display_notifications() ?>

<h1 class="h5"><?= $this->language->register->header ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <div class="form-group">
        <label><?= $this->language->register->form->name ?></label>
        <input type="text" name="name" class="form-control" value="<?= $data->values['name'] ?>" required="required" />
    </div>

    <div class="form-group">
        <label><?= $this->language->register->form->email ?></label>
        <input type="text" name="email" class="form-control" value="<?= $data->values['email'] ?>" required="required" />
    </div>

    <div class="form-group">
        <label><?= $this->language->register->form->password ?></label>
        <input type="password" name="password" class="form-control" value="<?= $data->values['password'] ?>" required="required" />
    </div>

    <div class="form-group">
        <?php $data->captcha->display() ?>
    </div>

    <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" name="accept" type="checkbox" required="required">
            <small class="form-text text-muted">
                <?= sprintf(
                $this->language->register->form->accept,
                '<a href="' . $this->settings->terms_and_conditions_url . '" target="_blank">' . $this->language->global->terms_and_conditions . '</a>',
                '<a href="' . $this->settings->privacy_policy_url . '" target="_blank">' . $this->language->global->privacy_policy . '</a>'
                ) ?>
            </small>
        </label>
    </div>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block"><?= $this->language->register->form->register ?></button>
    </div>
</form>


<div class="mt-5 text-center text-muted">
    <?= sprintf($this->language->register->display->login, '<a href="' . url('login') . '" class="font-weight-bold">' . $this->language->register->display->login_help . '</a>') ?></a>
</div>


<?php ob_start() ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

