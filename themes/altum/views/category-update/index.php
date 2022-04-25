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
                <li class="active" aria-current="page"><?= $this->language->category_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>


    <div class="d-flex align-items-baseline">
        <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->category_update->header, $data->category->name) ?></h1>
        <?= include_view(THEME_PATH . 'views/category/category_dropdown_button.php', ['id' => $data->category->category_id, 'external_url' => $data->store->full_url . $data->menu->url . '/' . $data->category->url]) ?>
    </div>
    <p>
        <a href="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url ?>" target="_blank">
            <img src="https://external-content.duckduckgo.com/ip3/<?= parse_url($data->store->full_url)['host'] ?>.ico" class="img-fluid icon-favicon mr-1" />

            <?= $data->store->full_url . $data->menu->url . '/' . $data->category->url ?>
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
                data-clipboard-text="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url ?>"
        >
            <i class="fa fa-fw fa-sm fa-copy"></i>
        </button>
    </p>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <label for="url"><i class="fa fa-fw fa-sm fa-link text-muted mr-1"></i> <?= $this->language->category->input->url ?></label>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><?= url('s/' . $data->store->url . '/' . $data->menu->url . '/') ?></span>
                </div>
                <input type="text" id="url" name="url" class="form-control" value="<?= $data->category->url ?>" placeholder="<?= $this->language->category->input->url_placeholder ?>" />
            </div>
            <small class="form-text text-muted"><?= $this->language->category->input->url_help ?></small>
        </div>

        <div class="form-group">
            <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->category->input->name ?></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $data->category->name ?>" placeholder="<?= $this->language->category->input->name_placeholder ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="description"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= $this->language->category->input->description ?></label>
            <input type="text" id="description" name="description" class="form-control" value="<?= $data->category->description ?>" />
            <small class="form-text text-muted"><?= $this->language->category->input->description_help ?></small>
        </div>

        <div class="custom-control custom-switch my-3">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->category->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= $this->language->category->input->is_enabled ?></label>
            <small class="form-text text-muted"><?= $this->language->category->input->is_enabled_help ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->update ?></button>
    </form>

</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
