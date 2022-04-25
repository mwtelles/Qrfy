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
                <li class="active" aria-current="page"><?= $this->language->s_cart->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <h1 class="h3"><?= $this->language->s_cart->header ?></h1>

    <div class="mb-5 d-none" id="cart">

    </div>

    <div class="mb-5 d-none" id="empty_cart">
        <div class="d-flex flex-column align-items-center justify-content-center d-none">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/s/no_data.svg' ?>" class="col-8 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->s_cart->no_data ?>" loading="lazy" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->s_cart->no_data ?></h2>
            <p class="text-muted"><?= $this->language->s_cart->no_data_help ?></p>
        </div>
    </div>

    <div class="mb-5 d-none" id="order_done">
        <div class="d-flex flex-column align-items-center justify-content-center d-none">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/s/order_done.svg' ?>" class="col-8 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->s_cart->order_done ?>" loading="lazy" />
            <h2 class="h4 text-muted mt-3"><?= $this->language->s_cart->order_done ?></h2>
            <p class="text-muted"><?= $this->language->s_cart->order_done_help ?></p>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    let cart_name = <?= json_encode($data->store->store_id . '_cart') ?>;

    /* Delete handler */
    let initiate_delete_handler = () => {
        document.querySelectorAll(`div[id^='item_'] .item-delete`).forEach(element => {
            element.addEventListener('click', event => {

                let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];

                if (cart.length) {

                    let item_generated_id = element.getAttribute('data-item-generated-id');

                    /* Find the item to be deleted from the cart */
                    let found_item_index = cart.findIndex(item => item.item_generated_id == item_generated_id);

                    cart.splice(found_item_index, 1);

                    /* Save the localstorage */
                    localStorage.setItem(cart_name, JSON.stringify(cart));

                    process_cart();
                }

                event.preventDefault();
            });
        });
    };

    /* Quantity handler */
    let initiate_quantity_handler = () => {
        document.querySelectorAll(`div[id^='item_'] .item-quantity`).forEach(element => {
            element.addEventListener('change', event => {

                let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];

                if (cart.length) {

                    let item_generated_id = element.getAttribute('data-item-generated-id');

                    /* Find the item to be deleted from the cart */
                    let found_item_index = cart.findIndex(item => item.item_generated_id == item_generated_id);

                    /* New quantity */
                    let new_quantity = event.currentTarget.value <= 0 || event.currentTarget.value >= 10 ? 1 : parseFloat(event.currentTarget.value);

                    cart[found_item_index].quantity = new_quantity;

                    /* Save the localstorage */
                    localStorage.setItem(cart_name, JSON.stringify(cart));

                    process_cart();
                }

                event.preventDefault();
            });
        });

    };

    let process_cart = () => {

        /* Some needed variables */
        let language = {
            remove: <?= json_encode($this->language->s_cart->remove) ?>,
            order: <?= json_encode($this->language->s_cart->order) ?>,
            total: <?= json_encode($this->language->s_cart->total) ?>,
            quantity: <?= json_encode($this->language->s_cart->quantity) ?>,

            name: <?= json_encode($this->language->s_cart->name) ?>,
            number: <?= json_encode($this->language->s_cart->number) ?>,
            phone: <?= json_encode($this->language->s_cart->phone) ?>,
            address: <?= json_encode($this->language->s_cart->address) ?>,
            message: <?= json_encode($this->language->s_cart->message) ?>,
            message_help: <?= json_encode($this->language->s_cart->message_help) ?>,

            type: <?= json_encode($this->language->s_cart->type) ?>,
            type_on_premise: <?= json_encode($this->language->s_cart->type_on_premise) ?>,
            type_takeaway: <?= json_encode($this->language->s_cart->type_takeaway) ?>,
            type_delivery: <?= json_encode($this->language->s_cart->type_delivery) ?>,
        };

        let token = <?= json_encode(\Altum\Middlewares\Csrf::get()) ?>;
        let currency = <?= json_encode($data->store->currency) ?>;
        let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];
        let total = 0;

        /* Enabled ordering types */
        let on_premise_ordering_is_enabled = <?= json_encode($data->store->on_premise_ordering_is_enabled) ?>;
        let takeaway_ordering_is_enabled = <?= json_encode($data->store->takeaway_ordering_is_enabled) ?>;
        let delivery_ordering_is_enabled = <?= json_encode($data->store->delivery_ordering_is_enabled) ?>;

        /* Check if the order has been sent */
        let current_url = new URL(window.location.href);

        if(current_url.searchParams.get('order') == 'done') {

            /* Show the success message */
            document.querySelector('#order_done').classList.remove('d-none');

            localStorage.removeItem(cart_name);

            setTimeout(() => {
                current_url.searchParams.delete('order');
                current_url.searchParams.delete('page');
                location.replace(current_url.toString());
            }, 10000);

        } else {

            /* Display the cart or empty cart message */
            if(cart.length) {

                let html = ``;

                for (let item of cart) {

                    /* Generate extras html */
                    let extras_html = '';

                    for (let item_extra of item.item_extras) {
                        extras_html += `
                        <div class="">
                            <small class="text-muted">&#8226; ${item_extra.name}</small>
                        </div>
                        `;
                    }

                    html += `
                        <div id="${'item_' + item.item_generated_id}" class="my-3 rounded p-3 bg-gray-50">
                            <div class="row">
                                <div class="col-8 col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="store-cart-image-wrapper mr-3">
                                            ${item.full_image ? `<img src="${item.full_image}" class="store-cart-image-background" loading="lazy" />` : ''}
                                        </div>

                                        <div class="d-flex flex-column">
                                            <div class="mr-3">
                                                <a href="${item.full_url}" class="font-weight-bold" target="_blank">${item.name}</a>
                                            </div>

                                            <div>
                                                ${extras_html}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 d-flex flex-lg-column justify-content-lg-center order-1 order-lg-0 mt-3 mt-lg-0">
                                    <div class="d-flex">
                                        <span class="text-muted mr-3">${language.quantity}</span>

                                        <div>
                                            <select name="" class="form-control form-control-sm item-quantity" data-item-generated-id="${item.item_generated_id}">
                                                <option value="1" ${item.quantity == 1 ? 'selected="selected"' : ''}>1</option>
                                                <option value="2" ${item.quantity == 2 ? 'selected="selected"' : ''}>2</option>
                                                <option value="3" ${item.quantity == 3 ? 'selected="selected"' : ''}>3</option>
                                                <option value="4" ${item.quantity == 4 ? 'selected="selected"' : ''}>4</option>
                                                <option value="5" ${item.quantity == 5 ? 'selected="selected"' : ''}>5</option>
                                                <option value="6" ${item.quantity == 6 ? 'selected="selected"' : ''}>6</option>
                                                <option value="7" ${item.quantity == 7 ? 'selected="selected"' : ''}>7</option>
                                                <option value="8" ${item.quantity == 8 ? 'selected="selected"' : ''}>8</option>
                                                <option value="9" ${item.quantity == 9 ? 'selected="selected"' : ''}>9</option>
                                                <option value="10" ${item.quantity == 10 ? 'selected="selected"' : ''}>10</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ml-3 ml-lg-0">
                                        <a href="#" class="item-delete" role="button" tabindex="0" data-item-generated-id="${item.item_generated_id}"><small>${language.remove}</small></a>
                                    </div>
                                </div>

                                <div class="col-4 col-lg-3 d-flex align-items-center justify-content-end order-0 order-lg-1">
                                    <div>
                                       <span class="font-weight-bold">${item.final_price * item.quantity}</span> <span class="text-muted">${currency}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    /* Add price to final */
                    total += parseFloat(item.final_price * item.quantity);
                }


                /* Generate the inputs with the selected values */
                let hidden_inputs = '';
                let index = 0;

                for(let item of cart) {

                    hidden_inputs += `
                        <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}" />
                        <input type="hidden" name="items[${index}][item_variant_id]" value="${item.item_variant_id ? item.item_variant_id : ''}" />
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}" />
                    `;

                    for(let item_extra of item.item_extras) {
                        hidden_inputs += `
                            <input type="hidden" name="items[${index}][extras][]" value="${item_extra.item_extra_id}" />
                        `;
                    }

                    index++;
                }

                html += `
                    <div class="d-flex justify-content-between my-4">
                        <div class="font-weight-bold">
                            ${language.total}
                        </div>

                        <div>
                            <span class="font-weight-bold">${total}</span> <span class="text-muted">${currency}</span>
                        </div>
                    </div>

                    <form action="" method="post" role="form">
                        <div class="my-4">
                            <input type="hidden" name="token" value="${token}" />
                            ${hidden_inputs}


                            <div class="form-group">
                                <label for="type">${language.type}</label>
                                <select id="type" name="type" class="form-control">
                                    ${on_premise_ordering_is_enabled ? `<option value="on_premise">${language.type_on_premise}</option>` : ``}
                                    ${takeaway_ordering_is_enabled ? `<option value="takeaway">${language.type_takeaway}</option>` : ``}
                                    ${delivery_ordering_is_enabled ? `<option value="delivery">${language.type_delivery}</option>` : ``}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">${language.name}</label>
                                <input type="text" id="name" name="name" class="form-control" value="" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="phone">${language.phone}</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="address">${language.address}</label>
                                <input type="text" id="address" name="address" class="form-control" value="" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="number">${language.number}</label>
                                <input type="number" min="0" step="1" id="number" name="number" class="form-control" value="1" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="message">${language.message}</label>
                                <textarea id="message" class="form-control" name="message"></textarea>
                                <small class="text-muted">${language.message_help}</small>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="btn btn-block btn-primary my-4">${language.order}</button>
                    </form>
                `;

                document.querySelector('#cart').innerHTML = html;
                document.querySelector('#cart').classList.remove('d-none');
                document.querySelector('#empty_cart').classList.add('d-none');

                /* Type hanlder */
                let initiate_type_handler = () => {
                    let type = document.querySelector('#type').value;

                    switch(type) {
                        case 'on_premise':
                            document.querySelector('#phone').parentElement.classList.add('d-none');
                            document.querySelector('#phone').removeAttribute('required');

                            document.querySelector('#address').parentElement.classList.add('d-none');
                            document.querySelector('#address').removeAttribute('required');

                            document.querySelector('#number').parentElement.classList.remove('d-none');
                            document.querySelector('#number').setAttribute('required', 'required');
                            break;

                        case 'takeaway':
                            document.querySelector('#phone').parentElement.classList.remove('d-none');
                            document.querySelector('#phone').setAttribute('required', 'required');

                            document.querySelector('#address').parentElement.classList.add('d-none');
                            document.querySelector('#address').removeAttribute('required');

                            document.querySelector('#number').parentElement.classList.add('d-none');
                            document.querySelector('#number').removeAttribute('required');
                            break;

                        case 'delivery':
                            document.querySelector('#phone').parentElement.classList.remove('d-none');
                            document.querySelector('#phone').setAttribute('required', 'required');

                            document.querySelector('#address').parentElement.classList.remove('d-none');
                            document.querySelector('#address').setAttribute('required', 'required');

                            document.querySelector('#number').parentElement.classList.add('d-none');
                            document.querySelector('#number').removeAttribute('required');
                            break;
                    }
                };

                document.querySelector('#type').addEventListener('change', initiate_type_handler);
                initiate_type_handler();

            } else {
                document.querySelector('#empty_cart').classList.remove('d-none');
                document.querySelector('#cart').classList.add('d-none');
            }

        }
        /* Handlers */
        initiate_quantity_handler();
        initiate_delete_handler();
    };

    process_cart();

    /* Listen for changes on the localstorage on other potential tabs */
    window.addEventListener('storage', () => {
        process_cart();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
