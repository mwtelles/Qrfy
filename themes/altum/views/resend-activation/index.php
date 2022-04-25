<?php defined('ALTUMCODE') || die() ?>

<?php display_notifications() ?>

<h1 class="h5"><?= $this->language->resend_activation->header ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <div class="form-group">
        <label><?= $this->language->resend_activation->form->email ?></label>
        <input type="text" name="email" class="form-control" value="<?= $data->values['email'] ?>" required="required" />
    </div>

    <div class="form-group">
        <?php $data->captcha->display() ?>
    </div>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= $this->language->global->submit ?></button>
    </div>
</form>

<div class="mt-5 text-center">
    <a href="login" class="text-muted"><?= $this->language->resend_activation->return ?></a>
</div>

<?php ob_start() ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

