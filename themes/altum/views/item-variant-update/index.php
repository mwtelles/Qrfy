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
                    <?= $this->language->item_variant->breadcrumb ?><i class="fa fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= $this->language->item_variant_update->breadcrumb ?></li>
            </ol>
        </small>
    </nav>


    <div class="d-flex align-items-baseline">
        <h1 class="h4 text-truncate mr-3"><?= $this->language->item_variant_update->header ?></h1>
        <?= include_view(THEME_PATH . 'views/item-variant/item_variant_dropdown_button.php', ['id' => $data->item_variant->item_variant_id, 'external_url' => url('s/' . $data->store->url . '/' . $data->menu->url . '/' . $data->category->url . '/' . $data->item->url)]) ?>
    </div>
    <p></p>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

        <?php foreach($data->item_options as $row): ?>
        <div class="form-group">
            <label for="item_option_id_<?= $row->item_option_id ?>"><?= $row->name ?></label>
            <select id="item_option_id_<?= $row->item_option_id ?>" name="item_options_ids[<?= $row->item_option_id ?>]" class="form-control">
                <?php foreach($row->options as $key => $value): ?>
                    <?php
                    $option = null;

                    foreach($data->item_variant->item_options_ids as $item_option) {
                        if($item_option->item_option_id == $row->item_option_id && $item_option->option == $key) {
                            $option = $key;
                            break;
                        }
                    }
                    ?>

                    <option value="<?= $key ?>" <?= $option == $key ? 'selected="selected"' : null ?>><?= $value ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <?php endforeach ?>

        <label for="price"><i class="fa fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= $this->language->item->input->price ?></label>
        <div class="mb-3">
            <div class="input-group">
                <input type="number" id="price" name="price" class="form-control" value="<?= $data->item_variant->price ?>" step="any" required="required" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= $data->store->currency ?></span>
                </div>
            </div>
        </div>

        <div class="custom-control custom-switch my-3">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->item_variant->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= $this->language->item_variant->input->is_enabled ?></label>
            <small class="form-text text-muted"><?= $this->language->item_variant->input->is_enabled_help ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= $this->language->global->update ?></button>
    </form>

</div>
