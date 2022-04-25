<?php defined('ALTUMCODE') || die() ?>

<?php display_notifications() ?>

<h1 class="h5"><?= $this->language->reset_password->header ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <input type="hidden" name="email" value="<?= $data->values['email'] ?>" class="form-control" />

    <div class="form-group">
        <label><?= $this->language->reset_password->form->new_password ?></label>
        <input type="password" name="new_password" class="form-control" required="required" />
    </div>

    <div class="form-group">
        <label><?= $this->language->reset_password->form->repeat_password ?></label>
        <input type="password" name="repeat_password" class="form-control" required="required" />
    </div>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= $this->language->global->submit ?></button>
    </div>
</form>

<div class="mt-5 text-center">
    <a href="login" class="text-muted"><?= $this->language->reset_password->return ?></a>
</div>
