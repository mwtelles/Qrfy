<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
        <i class="fa fa-fw fa-ellipsis-v mr-1"></i>

        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?= url('store-redirect?menu_id=' . $data->id) ?>" target="_blank"><i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= \Altum\Language::get()->menu->external_url ?></a>
            <a class="dropdown-item" href="<?= url('statistics?menu_id=' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-chart-line mr-1"></i> <?= \Altum\Language::get()->statistics->menu ?></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= url('menu/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-list mr-1"></i> <?= \Altum\Language::get()->menu->menu ?></a>
            <a class="dropdown-item" href="<?= url('menu-update/' . $data->id) ?>"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= \Altum\Language::get()->global->edit ?></a>
            <a href="#" data-toggle="modal" data-target="#menu_delete_modal" data-menu-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= \Altum\Language::get()->global->delete ?></a>
        </div>
    </a>
</div>
