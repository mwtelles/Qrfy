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
                <li>
                    <a href="<?= url('category/' . $data->category->category_id) ?>"><?= $this->language->category->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= url('item/' . $data->item->item_id) ?>"><?= $this->language->item->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= $this->language->item_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>


    <div class="d-flex align-items-baseline">
        <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->item_update->header, $data->item->name) ?></h1>
        <?= include_view(THEME_PATH . 'views/item/item_dropdown_button.php', ['id' => $data->item->item_id, 'external_url' => $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url]) ?>
    </div>
    <p>
        <a href="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url ?>" target="_blank">
            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->store->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

            <?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url ?>
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
                data-clipboard-text="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url ?>"
        >
            <i class="fa fa-fw fa-sm fa-copy"></i>
        </button>
    </p>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= $this->language->item->input->url ?></label>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><?= $data->store->full_url . $data->menu->url . '/' ?></span>
                </div>
                <input type="text" id="url" name="url" class="form-control" value="<?= $data->item->url ?>" placeholder="<?= $this->language->item->input->url_placeholder ?>" />
            </div>
            <small class="form-text text-muted"><?= $this->language->item->input->url_help ?></small>
        </div>

        <div class="form-group">
            <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->item->input->name ?></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $data->item->name ?>" placeholder="<?= $this->language->item->input->name_placeholder ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= $this->language->item->input->description ?></label>
            <input type="text" id="description" name="description" class="form-control" value="<?= $data->item->description ?>" />
            <small class="form-text text-muted"><?= $this->language->item->input->description_help ?></small>
        </div>

        <div class="form-group">
            <label for="image"><i class="fa fa-fw fa-sm fa-image text-muted mr-1"></i> <?= $this->language->item->input->image ?></label>
            <?php if(!empty($data->item->image)): ?>
                <div class="m-1 col-3">
                    <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'item_images/' . $data->item->image ?>" class="img-fluid" loading="lazy" />
                </div>
            <?php endif ?>
            <input id="image" type="file" name="image" accept=".gif, .png, .jpg, .jpeg, .svg" class="form-control-file" />
            <small class="form-text text-muted"><?= $this->language->item->input->image_help ?></small>
        </div>

        <label for="price"><i class="fa fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= $this->language->item->input->price ?></label>
        <div class="mb-3">
            <div class="input-group">
                <input type="number" id="price" name="price" class="form-control" value="<?= $data->item->price ?>" step="any" required="required" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= $data->store->currency ?></span>
                </div>
            </div>
        </div>

        <div class="custom-control custom-switch my-3">
            <input id="variants_is_enabled" name="variants_is_enabled" type="checkbox" class="custom-control-input" <?= $data->item->variants_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="variants_is_enabled"><?= $this->language->item->input->variants_is_enabled ?></label>
            <small class="form-text text-muted"><?= $this->language->item->input->variants_is_enabled_help ?></small>
        </div>

        <div class="custom-control custom-switch my-3">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->item->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= $this->language->item->input->is_enabled ?></label>
            <small class="form-text text-muted"><?= $this->language->item->input->is_enabled_help ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->update ?></button>
    </form>

</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
