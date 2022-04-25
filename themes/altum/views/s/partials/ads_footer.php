<?php
if(
    !empty($this->settings->ads->footer_restaurant)
    && !$data->store_user->plan_settings->no_ads
): ?>
    <div class="container my-3"><?= $this->settings->ads->footer_restaurant ?></div>
<?php endif ?>
