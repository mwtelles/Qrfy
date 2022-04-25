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
                <li>
                    <?= $this->language->item_option->breadcrumb ?><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= $this->language->item_option_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>


    <div class="d-flex align-items-baseline">
        <h1 class="h4 text-truncate mr-3"><?= sprintf($this->language->item_option_update->header, $data->item_option->name) ?></h1>
        <?= include_view(THEME_PATH . 'views/item-option/item_option_dropdown_button.php', ['id' => $data->item_option->item_option_id, 'external_url' => url('s/' . $data->store->url . '/' . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url)]) ?>
    </div>
    <p></p>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <div class="form-group">
            <label for="name"><i class="fa fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= $this->language->item_option->input->name ?></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $data->item_option->name ?>" placeholder="<?= $this->language->item_option->input->name_placeholder ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="options"><i class="fa fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= $this->language->item_option->input->options ?></label>
            <input type="text" id="options" name="options" class="form-control" value="<?= implode(',', $data->item_option->options) ?>" />
            <small class="form-text text-muted"><?= $this->language->item_option->input->options_help ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->update ?></button>
    </form>

</div>
