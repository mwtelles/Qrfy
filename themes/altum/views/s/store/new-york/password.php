<?php defined('ALTUMCODE') || die() ?>

<div class="container mt-8">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="mb-4 d-flex">
                <div>
                    <h1 class="h3"><?= $this->language->s_store->password->header  ?></h1>
                    <span class="text-muted">
                        <?= $this->language->s_store->password->subheader ?>
                    </span>
                </div>
            </div>

            <?php display_notifications(false) ?>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="password"><?= $this->language->s_store->password->input ?></label>
                    <input type="password" id="password" name="password" value="" class="form-control" required="required" />
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->submit ?></button>
            </form>

        </div>
    </div>
</div>


