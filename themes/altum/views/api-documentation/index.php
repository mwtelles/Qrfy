<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li><a href="<?= url() ?>"><?= $this->language->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= $this->language->api_documentation->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="row mb-7">
        <div class="col-12 col-lg-7 mb-4 mb-lg-0">
            <h1 class="h4"><?= $this->language->api_documentation->header ?></h1>
            <p class="text-muted"><?= $this->language->api_documentation->subheader ?></p>
        </div>

        <div class="col-12 col-lg-4 offset-lg-1">

            <div class="mb-3">
                <a href="<?= url('account-api') ?>" target="_blank" class="btn btn-block btn-outline-primary"><?= $this->language->api_documentation->api_key ?></a>
            </div>

            <div class="form-group">
                <label for="base_url"><?= $this->language->api_documentation->base_url ?></label>
                <input type="text" id="base_url" value="<?= SITE_URL . 'api' ?>" class="form-control" readonly="readonly" />
            </div>

        </div>
    </div>

    <div class="mb-7">

        <div class="mb-4">
            <h2 class="h5"><?= $this->language->api_documentation->authentication->header ?></h2>
            <p class="text-muted"><?= $this->language->api_documentation->authentication->subheader ?></p>
        </div>

        <div class="form-group">
            <label><?= $this->language->api_documentation->example ?></label>
            <div class="card bg-gray-50 border-0">
                <div class="card-body">
                    curl --request GET \<br />
                    --url '<?= SITE_URL . 'api/' ?><span class="text-primary">{endpoint}</span>' \<br />
                    --header 'authorization: Bearer <span class="text-primary">{api_key}</span>' \
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-7" />

    <div class="">
        <div class="mb-5">
            <h2 class="h5"><?= $this->language->api_documentation->user->header ?></h2>
            <p class="text-muted"><?= $this->language->api_documentation->user->subheader ?></p>
        </div>

        <div class="mb-4">
            <h3 class="h6"><?= $this->language->api_documentation->user->get->header ?></h3>
            <p class="text-muted"><?= $this->language->api_documentation->user->get->subheader ?></p>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7 mb-4 mb-lg-0">

                <div class="form-group mb-4">
                    <label><?= $this->language->api_documentation->endpoint ?></label>
                    <div class="card bg-gray-50 border-0">
                        <div class="card-body">
                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/user</span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="card bg-gray-50 border-0">
                        <div class="card-header">
                            <small class="text-muted"><?= $this->language->api_documentation->example ?></small>
                        </div>
                        <div class="card-body">
                            curl --request GET \<br />
                            --url '<?= SITE_URL ?>api/user' \<br />
                            --header 'authorization: Bearer <span class="text-primary">{api_key}</span>' \
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="card bg-gray-50 border-0">
                        <div class="card-header">
                            <small class="text-muted"><?= $this->language->api_documentation->response ?></small>
                        </div>
                        <pre class="card-body">
{
    "data": {
        "id":"1",
        "type":"users",
        "email":"example@example.com",
        "billing":{
            "type":"personal",
            "name":"John Doe",
            "address":"Lorem Ipsum",
            "city":"Dolor Sit",
            "county":"Amet",
            "zip":"5000",
            "country":"",
            "phone":"",
            "tax_id":""
        },
        "active":true,
        "plan_id":"custom",
        "plan_expiration_date":"2025-12-12 00:00:00",
        "plan_settings":{
            "stores_limit":1,
            "menus_limit":10,
            "no_ads":true,
            "analytics_is_enabled":true,
            "removable_branding_is_enabled":true,
            "custom_url_is_enabled":true,
            "password_protection_is_enabled":true,
            "search_engine_block_is_enabled":true,
            "custom_css_is_enabled":true,
            "custom_js_is_enabled":true,
            "email_reports_is_enabled":true
        },
        "plan_trial_done":false,
        "language":"english",
        "timezone":"UTC",
        "country":null,
        "date":"2020-01-01 00:00:00",
        "last_activity":"2020-01-01 00:00:00",
        "total_logins":10
    }
}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
