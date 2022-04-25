<?php defined('ALTUMCODE') || die() ?>

<div class="mb-5 row justify-content-between">
    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-store mr-1"></i> <?= $this->language->admin_index->display->stores_month ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->stores->stores_month) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-users mr-1"></i> <?= $this->language->admin_index->display->active_users_month ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->users->active_users_month) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-funnel-dollar mr-1"></i> <?= $this->language->admin_index->display->payments_month ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->payments_month->payments) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= $this->language->admin_index->display->earnings_month ?></small>

                <div class="mt-3"><span class="h4"><?= $data->payments_month->earnings ?></span> <small><?= $this->settings->payment->currency ?></small></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-store mr-1"></i> <?= $this->language->admin_index->display->stores ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->stores->stores) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/websites') ?>">
                    <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-users mr-1"></i> <?= $this->language->admin_index->display->active_users ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->users->active_users) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/users') ?>">
                    <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-funnel-dollar mr-1"></i> <?= $this->language->admin_index->display->payments ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->payments->payments) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/payments') ?>">
                    <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= $this->language->admin_index->display->earnings ?></small>

                <div class="mt-3"><span class="h4"><?= $data->payments->earnings ?></span> <small><?= $this->settings->payment->currency ?></small></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/payments') ?>">
                    <i class="fa fa-fw fa-arrow-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mb-5">
    <h1 class="h3 mb-4"><?= $this->language->admin_index->users->header ?></h1>

    <?php $result = \Altum\Database\Database::$database->query("SELECT `user_id`, `name`, `email`, `active`, `date` FROM `users` ORDER BY `user_id` DESC LIMIT 5"); ?>
    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= $this->language->admin_index->users->user ?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_object()): ?>
                <tr>
                    <td>
                        <div class="d-flex">
                            <img src="<?= get_gravatar($row->email) ?>" class="user-avatar rounded-circle mr-3" alt="" />

                            <div class="d-flex flex-column">
                                <?= '<a href="' . url('admin/user-view/' . $row->user_id) . '">' . $row->name . '</a>' ?>

                                <span class="text-muted"><?= $row->email ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <div><?= $row->active ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>' ?></div>
                                <div><small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>"><?= \Altum\Date::get($row->date, 2) ?></small></div>
                            </div>
                        </div>
                    </td>
                    <td><?= include_view(THEME_PATH . 'views/admin/partials/admin_user_dropdown_button.php', ['id' => $row->user_id]) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
    </div>
</div>

<?php $result = \Altum\Database\Database::$database->query("SELECT `payments`.*, `users`.`name` AS `user_name` FROM `payments` LEFT JOIN `users` ON `payments`.`user_id` = `users`.`user_id` ORDER BY `id` DESC LIMIT 5"); ?>

<?php if($result->num_rows): ?>
    <div class="mb-5">
        <h1 class="h3 mb-4"><?= $this->language->admin_index->payments->header ?></h1>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th><?= $this->language->admin_index->payments->user ?></th>
                        <th><?= $this->language->admin_index->payments->payment ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_object()): ?>

                    <tr>
                        <td>
                            <?= '<a href="' . url( 'admin/user-view/' . $row->user_id) . '">' . $row->user_name . '</a>' ?>
                        </td>

                        <td>
                            <div class="d-flex flex-column">
                                <span><?= $row->email ?></span>
                                <span class="text-muted"><?= $row->name ?></span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-column">
                                <span><?= $this->language->pay->custom_plan->{$row->type . '_type'} ?></span>
                                <span class="text-muted"><?= $this->language->pay->custom_plan->{strtolower($row->processor)} ?></span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-column">
                                <span><span class="text-success"><?= $row->total_amount ?></span> <?= $row->currency ?></span>
                                <span class="text-muted"><span data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>"><?= \Altum\Date::get($row->date, 2) ?></span></span>
                            </div>
                        </td>
                    </tr>

                <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif ?>

<div class="card">
    <div class="card-body">

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-code fa-sm mr-1"></i> Version
                </span>
            </div>
            <div class="col-12 col-md-6">
                <?= PRODUCT_VERSION ?>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-project-diagram fa-sm mr-1"></i> More work of mine
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="https://github.com/mwtelles" target="_blank">Github</a>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-globe fa-sm mr-1"></i> Official website
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="https://mwtelles.com" target="_blank">mwtelles</a>
            </div>
        </div>
    </div>
</div>
