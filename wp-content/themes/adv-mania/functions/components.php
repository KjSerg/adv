<?php
function adv_mania_advance_form_render() {
	?>
    <div class="booking-advance text-section">
		<p><?php echo pll_e( 'Advance payment for booking 20%' ) ?></p>
        <strong class="booking-advance__value"></strong>
    </div>
	<?php
}

function adv_mania_promo_form_render() {
	?>
    <div class="promo-code-form tour-form">
        <label style="margin-bottom: 0;" class="pay-item pay-number">
            <span class="pay-info-label">Promo code</span>
            <input type="text" class="input-tr" id="promocode" name="promo_code"
                   placeholder="Enter code">
        </label>
        <a href="#" class="btn btn-red set-promo-js"><?php echo pll_e( 'apply' ) ?></a>
    </div>
	<?php
}