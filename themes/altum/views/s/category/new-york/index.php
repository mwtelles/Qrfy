<?php defined('ALTUMCODE') || die() ?>

<?= $this->views['header'] ?>

<?php require THEME_PATH . 'views/s/partials/ads_header.php' ?>

<div class="container mt-5">

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li>
                    <a href="<?= $data->store->full_url ?>"><?= $this->language->s_store->breadcrumb ?></a> <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/chevron-right.svg') ?></div>
                </li>
                <li>
                    <a href="<?= $data->store->full_url . $data->menu->url ?>"><?= $this->language->s_menu->breadcrumb ?></a> <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . '/images/s/chevron-right.svg') ?></div>
                </li>
                <li class="active" aria-current="page"><?= $this->language->s_category->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h3"><?= $data->category->name ?></h1>
    <p class="text-muted"><?= $data->category->description ?></p>

    <div class="row">
        <?php foreach($data->items as $item): ?>
            <div class="col-12 col-lg-6">
                <div class="d-flex position-relative my-3 rounded p-3 bg-gray-50">
                    <div class="store-item-image-wrapper mr-4">
                        <?php if(!empty($item->image)): ?>
                        <img src="<?= SITE_URL . UPLOADS_URL_PATH . 'item_images/' . $item->image ?>" class="store-item-image-background" loading="lazy" />
                        <?php endif ?>
                    </div>

                    <div class="d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 mb-1">
                                <a href="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url . '/' . $item->url ?>" class="stretched-link">
                                    <?= $item->name ?>
                                </a>
                            </h3>

                            <p class="mt-1 text-muted"><?= string_truncate($item->description, 100) ?></p>
                        </div>

                        <div class="mt-3">
                            <span class="h5 text-black">
                                <?= $item->price ?>
                            </span>
                            <span class="text-muted">
                                <?= $data->store->currency ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

</div>

<?= include_view(THEME_PATH . 'views/s/partials/share.php', ['external_url' => $data->store->full_url . $data->menu->url . '/' . $data->category->url]) ?>
