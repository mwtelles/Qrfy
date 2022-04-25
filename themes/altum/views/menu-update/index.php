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
                <li>
                    <a href="<?= url('menu/' . $data->menu->menu_id) ?>"><?= $this->language->menu->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= $this->language->menu_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->menu_update->header, $data->menu->name) ?></h1>
    <p>
        <a href="<?= $data->store->full_url . $data->menu->url ?>" target="_blank">
            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->store->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

            <?= $data->store->full_url . $data->menu->url ?>
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
                data-clipboard-text="<?= $data->store->full_url . $data->menu->url ?>"
        >
            <i class="fa fa-fw fa-sm fa-copy"></i>
        </button>
    </p>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= $this->language->menu->input->url ?></label>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><?= $data->store->full_url ?></span>
                </div>
                <input type="text" id="url" name="url" class="form-control" value="<?= $data->menu->url ?>" placeholder="<?= $this->language->menu->input->url_placeholder ?>" />
            </div>
            <small class="form-text text-muted"><?= $this->language->menu->input->url_help ?></small>
        </div>

        <div class="form-group">
            <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->menu->input->name ?></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $data->menu->name ?>" placeholder="<?= $this->language->menu->input->name_placeholder ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= $this->language->menu->input->description ?></label>
            <input type="text" id="description" name="description" class="form-control" value="<?= $data->menu->description ?>" />
            <small class="form-text text-muted"><?= $this->language->menu->input->description_help ?></small>
        </div>

        <div class="form-group">
            <label for="image"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= $this->language->menu->input->image ?></label>
            <?php if(!empty($data->menu->image)): ?>
                <div class="m-1 col-3">
                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'menu_images/' . $data->menu->image ?>" class="img-fluid" loading="lazy" />
                </div>
            <?php endif ?>
            <input id="image" type="file" name="image" accept=".gif, .png, .jpg, .jpeg, .svg" class="form-control-file" />
            <small class="form-text text-muted"><?= $this->language->menu->input->image_help ?></small>
        </div>

        <div class="custom-control custom-switch my-3">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->menu->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= $this->language->menu->input->is_enabled ?></label>
            <small class="form-text text-muted"><?= $this->language->menu->input->is_enabled_help ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->update ?></button>
    </form>

</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

