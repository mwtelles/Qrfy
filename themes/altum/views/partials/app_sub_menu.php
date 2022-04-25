<?php defined('ALTUMCODE') || die() ?>

<nav class="navbar app-sub-navbar navbar-expand-lg navbar-light bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-start align-items-lg-center overflow-y">

        <ul class="app-sub-navbar-ul">

            <?php if(in_array(\Altum\Routing\Router::$controller_key, ['store', 'store-qr', 'store-update', 'orders']) || (\Altum\Routing\Router::$controller_key == 'statistics' && isset($_GET['store_id']))): ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'store' ? 'active' : null ?>" href="<?= url('store/' . $data->store_id) ?>">
                        <i class="fa fa-fw fa-sm fa-store mr-1"></i> <?= $this->language->store->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'store-qr' ? 'active' : null ?>" href="<?= url('store-qr/' . $data->store_id) ?>">
                        <i class="fa fa-fw fa-sm fa-qrcode mr-1"></i> <?= $this->language->store_qr->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'orders' ? 'active' : null ?>" href="<?= url('orders/' . $data->store_id) ?>">
                        <i class="fa fa-fw fa-sm fa-bell mr-1"></i> <?= $this->language->orders->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->store->external_url ?>
                    </a>
                </li>

                <div <?= $this->user->plan_settings->analytics_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="<?= $this->user->plan_settings->analytics_is_enabled ? null : 'container-disabled' ?>">

                        <li class="nav-item">
                            <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'statistics' ? 'active' : null ?>" href="<?= url('statistics?store_id=' . $data->store_id) ?>">
                                <i class="fa fa-fw fa-sm fa-chart-line mr-1"></i> <?= $this->language->statistics->menu ?>
                            </a>
                        </li>

                    </div>
                </div>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'store-update' ? 'active' : null ?>" href="<?= url('store-update/' . $data->store_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#store_delete_modal" data-store-id="<?= $data->store_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['order'])): ?>

                <li class="nav-item">
                    <span class="nav-link <?= \Altum\Routing\Router::$controller_key == 'order' ? 'active' : null ?>">
                        <i class="fa fa-fw fa-sm fa-bell mr-1"></i> <?= $this->language->order->menu ?>
                    </span>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#order_delete_modal" data-store-id="<?= $data->order_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['menu', 'menu-update']) || (\Altum\Routing\Router::$controller_key == 'statistics' && isset($_GET['menu_id']))): ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'menu' ? 'active' : null ?>" href="<?= url('menu/' . $data->menu_id) ?>">
                        <i class="fa fa-fw fa-sm fa-list mr-1"></i> <?= $this->language->menu->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->menu->external_url ?>
                    </a>
                </li>

                <div <?= $this->user->plan_settings->analytics_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="<?= $this->user->plan_settings->analytics_is_enabled ? null : 'container-disabled' ?>">

                        <li class="nav-item">
                            <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'statistics' ? 'active' : null ?>" href="<?= url('statistics?menu_id=' . $data->menu_id) ?>">
                                <i class="fa fa-fw fa-sm fa-chart-line mr-1"></i> <?= $this->language->statistics->menu ?>
                            </a>
                        </li>

                    </div>
                </div>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'menu-update' ? 'active' : null ?>" href="<?= url('menu-update/' . $data->menu_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#menu_delete_modal" data-menu-id="<?= $data->menu_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['category', 'category-update']) || (\Altum\Routing\Router::$controller_key == 'statistics' && isset($_GET['category_id']))): ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'category' ? 'active' : null ?>" href="<?= url('category/' . $data->category_id) ?>">
                        <i class="fa fa-fw fa-sm fa-shopping-bag mr-1"></i> <?= $this->language->category->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->category->external_url ?>
                    </a>
                </li>

                <div <?= $this->user->plan_settings->analytics_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="<?= $this->user->plan_settings->analytics_is_enabled ? null : 'container-disabled' ?>">

                        <li class="nav-item">
                            <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'statistics' ? 'active' : null ?>" href="<?= url('statistics?category_id=' . $data->category_id) ?>">
                                <i class="fa fa-fw fa-sm fa-chart-line mr-1"></i> <?= $this->language->statistics->menu ?>
                            </a>
                        </li>

                    </div>
                </div>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'category-update' ? 'active' : null ?>" href="<?= url('category-update/' . $data->category_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#category_delete_modal" data-category-id="<?= $data->category_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['item', 'item-update']) || (\Altum\Routing\Router::$controller_key == 'statistics' && isset($_GET['item_id']))): ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'item' ? 'active' : null ?>" href="<?= url('item/' . $data->item_id) ?>">
                        <i class="fa fa-fw fa-sm fa-burn mr-1"></i> <?= $this->language->item->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->item->external_url ?>
                    </a>
                </li>

                <div <?= $this->user->plan_settings->analytics_is_enabled ? null : 'data-toggle="tooltip" title="' . $this->language->global->info_message->plan_feature_no_access . '"' ?>>
                    <div class="<?= $this->user->plan_settings->analytics_is_enabled ? null : 'container-disabled' ?>">

                        <li class="nav-item">
                            <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'statistics' ? 'active' : null ?>" href="<?= url('statistics?item_id=' . $data->item_id) ?>">
                                <i class="fa fa-fw fa-sm fa-chart-line mr-1"></i> <?= $this->language->statistics->menu ?>
                            </a>
                        </li>

                    </div>
                </div>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'item-update' ? 'active' : null ?>" href="<?= url('item-update/' . $data->item_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#item_delete_modal" data-item-id="<?= $data->item_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['item-extra-update'])): ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->item->external_url ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'item-extra-update' ? 'active' : null ?>" href="<?= url('item-extra-update/' . $data->item_extra_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#item_extra_delete_modal" data-item-extra-id="<?= $data->item_extra_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['item-option-update'])): ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->item->external_url ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'item-option-update' ? 'active' : null ?>" href="<?= url('item-option-update/' . $data->item_option_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#item_option_delete_modal" data-item-option-id="<?= $data->item_option_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php elseif(in_array(\Altum\Routing\Router::$controller_key, ['item-variant-update'])): ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $data->external_url ?>" target="_blank">
                        <i class="fa fa-fw fa-sm fa-external-link-alt mr-1"></i> <?= $this->language->item->external_url ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'item-variant-update' ? 'active' : null ?>" href="<?= url('item-variant-update/' . $data->item_variant_id) ?>">
                        <i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= $this->language->global->edit ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#item_variant_delete_modal" data-item-variant-id="<?= $data->item_variant_id ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->global->delete ?>
                    </a>
                </li>

            <?php else: ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account' ? 'active' : null ?>" href="<?= url('account') ?>">
                        <i class="fa fa-fw fa-sm fa-wrench mr-1"></i> <?= $this->language->account->menu ?>
                    </a>
                </li>

                <?php if($this->settings->stores->domains_is_enabled): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'domains' ? 'active' : null ?>" href="<?= url('domains') ?>">
                            <i class="fa fa-fw fa-sm fa-globe mr-1"></i> <?= $this->language->domains->menu ?>
                        </a>
                    </li>
                <?php endif ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-plan' ? 'active' : null ?>" href="<?= url('account-plan') ?>">
                        <i class="fa fa-fw fa-sm fa-box-open mr-1"></i> <?= $this->language->account_plan->menu ?>
                    </a>
                </li>

                <?php if($this->settings->payment->is_enabled): ?>
                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-payments' ? 'active' : null ?>" href="<?= url('account-payments') ?>">
                        <i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= $this->language->account_payments->menu ?>
                    </a>
                </li>
                <?php endif ?>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-logs' ? 'active' : null ?>" href="<?= url('account-logs') ?>">
                        <i class="fa fa-fw fa-sm fa-scroll mr-1"></i> <?= $this->language->account_logs->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-api' ? 'active' : null ?>" href="<?= url('account-api') ?>">
                        <i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= $this->language->account_api->menu ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= \Altum\Routing\Router::$controller_key == 'account-delete' ? 'active' : null ?>" href="<?= url('account-delete') ?>">
                        <i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= $this->language->account_delete->menu ?>
                    </a>
                </li>

            <?php endif ?>

        </ul>

    </div>
</nav>
