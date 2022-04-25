<?php defined('ALTUMCODE') || die() ?>

<div class="container d-flex flex-column flex-md-row align-items-md-center my-5">
    <span class="text-muted mb-2 mb-md-0 mr-md-3"><?= \Altum\Language::get()->s_store->share->header ?></span>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $data->external_url ?>" target="_blank" class="btn btn-sm btn-link mb-2 mb-md-0 mr-md-3"><?= \Altum\Language::get()->s_store->share->facebook ?></a>
    <a href="http://twitter.com/share?url=<?= $data->external_url ?>" target="_blank" class="btn btn-sm btn-link mb-2 mb-md-0 mr-md-3"><?= \Altum\Language::get()->s_store->share->twitter ?></a>
    <a href="fb-messenger://share/?link=<?= $data->external_url ?>" class="btn btn-sm btn-link mb-2 mb-md-0 mr-md-3"><?= \Altum\Language::get()->s_store->share->facebook_messenger ?></a>
    <a href="mailto:?body=<?= $data->external_url ?>" target="_blank" class="btn btn-sm btn-link mb-2 mb-md-0 mr-md-3"><?= \Altum\Language::get()->s_store->share->email ?></a>
    <a href="whatsapp://send?text=<?= $data->external_url ?>" class="btn btn-sm btn-link mb-2 mb-md-0 mr-md-3"><?= \Altum\Language::get()->s_store->share->whatsapp ?></a>
</div>