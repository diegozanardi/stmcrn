<div class="col-md-12">
<div class="table-responsive table-wrapper-scroll-y">
  <h3>Detalle de la factura</h3>
  <br>
    <table id="item_table" class="table table-hover table-white">
        <thead>
        <tr style="display:none;">
            <th class="text-center">#</th>
            <th class="text-center"><?php _trans('item'); ?></th>
            <th class="text-center"><?php _trans('quantity'); ?></th>
            <th class="text-center"><?php _trans('price'); ?></th>
            <th class="text-center"><?php _trans('total'); ?></th>
            <th></th>
        </tr>
        </thead>

        <tbody id="new_row" style="display: none;">
        <tr>
            <td rowspan="2" class="td-icon">
                <i class="fa fa-arrows cursor-move"></i>
                <?php if ($invoice->invoice_is_recurring) : ?>
                    <br/>
                    <i title="<?php echo trans('recurring') ?>"
                       class="js-item-recurrence-toggler cursor-pointer fa fa-calendar-o text-muted"></i>
                    <input type="hidden" name="item_is_recurring" value=""/>
                <?php endif; ?>
            </td>
            <td>
                <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="item_product_id" value="">
                <input type="hidden" name="item_task_id" class="item-task-id" value="">
                <label for=""><?php _trans('item'); ?></label>
                <input type="text" name="item_name" class="form-control" value="">

            </td>
            <td>
              <label for=""><?php _trans('quantity'); ?></label>
              <input type="number" name="item_quantity" class="form-control amount" value="">
            </td>
            <td>
              <label for=""><?php _trans('price'); ?></label>
              <div class="input-group">
                <div class="input-group-addon">
                <i class="fa fa-usd"> </i>
              </div>
              <input id="item_prie" type="text" name="item_price" class="form-control amount" value="">
            </div>
            </td>
            <td style="display:none;"><input type="text" name="item_discount_amount" class="form-control"
                           value="" data-toggle="tooltip" data-placement="bottom"
                           title="<?php echo get_setting('currency_symbol') . ' ' . trans('per_item'); ?>">

            </td>

            <?php if ($invoice->sumex_id == ""): ?>
                <td class="td-textarea" style="display:none;">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('description'); ?></span>
                        <textarea name="item_description" class="input-sm form-control"></textarea>
                    </div>
                </td>
            <?php else: ?>
                <td class="td-date">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('date'); ?></span>
                        <input type="text" name="item_date" class="input-sm form-control datepicker"
                               value="<?php echo format_date(@$item->item_date); ?>"
                            <?php if ($invoice->is_read_only == 1) {
                                echo 'disabled="disabled"';
                            } ?>>
                    </div>
                </td>
            <?php endif; ?>
            <td class="td-amount text-right">
              <label for=""><?php _trans('total'); ?></label>
              <div class="input-group">
                <div class="input-group-addon">
                <i class="fa fa-usd"> </i>
              </div>
              <h4 class="form-control total"></h4>
            </div>
            </td>
            <td class="td-icon text-right td-vert-middle">
                <button type="button" class="btn_delete_item btn btn-link btn-sm" title="<?php _trans('delete'); ?>">
                    <i class="fa fa-trash-o text-danger"></i>
                </button>
            </td>

        </tr>
        <tr style="display:none;">
          <td>
              <div class="input-group hide">
                  <span class="input-group-addon"><?php _trans('tax_rate'); ?></span>
                  <select name="item_tax_rate_id" class="form-control input-sm">
                      <option value="0"><?php _trans('none'); ?></option>
                      <?php foreach ($tax_rates as $tax_rate) { ?>
                          <option value="<?php echo $tax_rate->tax_rate_id; ?>"
                              <?php check_select(get_setting('default_item_tax_rate'), $tax_rate->tax_rate_id); ?>>
                              <?php echo format_amount($tax_rate->tax_rate_percent) . '% - ' . $tax_rate->tax_rate_name; ?>
                          </option>
                      <?php } ?>
                  </select>
              </div>
          </td>
            <td class="td-amount">
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('product_unit'); ?></span>
                    <select name="item_product_unit_id" class="form-control">
                        <option value="0"><?php _trans('none'); ?></option>
                        <?php foreach ($units as $unit) { ?>
                            <option value="<?php echo $unit->unit_id; ?>">
                                <?php echo $unit->unit_name . "/" . $unit->unit_name_plrl; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td class="td-amount td-vert-middle">
                <span><?php _trans('subtotal'); ?></span><br/>
                <span name="subtotal" class="amount"></span>
            </td>

            <td class="td-amount td-vert-middle">
                <span><?php _trans('total'); ?></span><br/>
                <span name="item_total" class="amount"></span>
            </td>

        </tr>
        <?php foreach ($items as $item) { ?>
            <tbody class="item">
            <tr>
                <td rowspan="2" class="td-icon">
                    <i class="fa fa-arrows cursor-move"></i>
                    <?php
                    if ($invoice->invoice_is_recurring) :
                        if ($item->item_is_recurring == 1 || is_null($item->item_is_recurring)) {
                            $item_recurrence_state = '1';
                            $item_recurrence_class = 'fa-calendar-check-o text-success';
                        } else {
                            $item_recurrence_state = '0';
                            $item_recurrence_class = 'fa-calendar-o text-muted';
                        }
                        ?>
                        <br/>
                        <i title="<?php echo trans('recurring') ?>"
                           class="js-item-recurrence-toggler cursor-pointer fa <?php echo $item_recurrence_class ?>"></i>
                        <input type="hidden" name="item_is_recurring" value="<?php echo $item_recurrence_state ?>"/>
                    <?php endif; ?>
                </td>



                <td class="td-text">
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
                    <input type="hidden" name="item_id" value="<?php echo $item->item_id; ?>"
                        <?php if ($invoice->is_read_only == 1) {
                            echo 'disabled="disabled"';
                        } ?>>
                    <input type="hidden" name="item_task_id" class="item-task-id"
                           value="<?php if ($item->item_task_id) {
                               echo $item->item_task_id;
                           } ?>">
                    <input type="hidden" name="item_product_id" value="<?php echo $item->item_product_id; ?>">

                        <label for=""><?php _trans('item'); ?></label>
                        <input type="text" name="item_name" class="form-control"
                               value="<?php _htmlsc($item->item_name); ?>"
                            <?php if ($invoice->is_read_only == 1) {
                                echo 'disabled="disabled"';
                            } ?>>

                </td>
                <td class="td-amount td-quantity">
                        <label for=""><?php _trans('quantity'); ?></label>
                        <input type="text" name="item_quantity" class="form-control amount"
                               value="<?php echo format_amount($item->item_quantity); ?>"
                            <?php if ($invoice->is_read_only == 1) {
                                echo 'disabled="disabled"';
                            } ?>>

                </td>
                <td class="td-amount">
                <label for=""><?php _trans('price'); ?></label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-usd"> </i>
                </div>
                <input type="text" name="item_price" class="form-control amount"
                               value="<?php echo format_amount($item->item_price); ?>"
                            <?php if ($invoice->is_read_only == 1) {
                                echo 'disabled="disabled"';
                            } ?>>
                </div>
                </td>

                <td class="td-amount text-right">
                  <label for=""><?php _trans('total'); ?></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                    <i class="fa fa-usd"> </i>
                  </div>
                  <h4 class="form-control total"><?php echo format_amount($item->item_total); ?></h4>
                </div>
                </td>
                <td class="td-amount" style="display:none;">

                        <input type="text" name="item_discount_amount" class="form-control"
                               value="<?php echo format_amount($item->item_discount_amount); ?>"
                               data-toggle="tooltip" data-placement="bottom"
                               title="<?php echo get_setting('currency_symbol') . ' ' . trans('per_item'); ?>"
                            <?php if ($invoice->is_read_only == 1) {
                                echo 'disabled="disabled"';
                            } ?>>

                </td>
                <td class="td-amount" style="display:none;">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('tax_rate'); ?></span>
                        <select name="item_tax_rate_id" class="form-control input-sm"
                            <?php if ($invoice->is_read_only == 1) {
                                echo 'disabled="disabled"';
                            } ?>>
                            <option value="0"><?php _trans('none'); ?></option>
                            <?php foreach ($tax_rates as $tax_rate) { ?>
                                <option value="<?php echo $tax_rate->tax_rate_id; ?>"
                                    <?php check_select($item->item_tax_rate_id, $tax_rate->tax_rate_id); ?>>
                                    <?php echo format_amount($tax_rate->tax_rate_percent) . '% - ' . $tax_rate->tax_rate_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td class="td-icon text-right td-vert-middle">
                    <?php if ($invoice->is_read_only != 1): ?>
                        <button type="button" class="btn_delete_item btn btn-link btn-sm" title="<?php _trans('delete'); ?>"
                                data-item-id="<?php echo $item->item_id; ?>">
                            <i class="fa fa-trash-o text-danger"></i>
                        </button>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <?php if ($invoice->sumex_id == ""): ?>
                    <td class="td-textarea" style="display:none;">
                        <div class="input-group">
                            <span class="input-group-addon"><?php _trans('description'); ?></span>
                            <textarea name="item_description"
                                      class="input-sm form-control"
                                <?php if ($invoice->is_read_only == 1) {
                                    echo 'disabled="disabled"';
                                } ?>><?php echo htmlsc($item->item_description); ?></textarea>
                        </div>
                    </td>
                <?php else: ?>
                    <td class="td-date">
                        <div class="input-group">
                            <span class="input-group-addon"><?php _trans('date'); ?></span>
                            <input type="text" name="item_date" class="input-sm form-control datepicker"
                                   value="<?php echo format_date($item->item_date); ?>"
                                <?php if ($invoice->is_read_only == 1) {
                                    echo 'disabled="disabled"';
                                } ?>>
                        </div>
                    </td>
                <?php endif; ?>

                <td class="td-amount" style="display:none;">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('product_unit'); ?></span>
                        <select name="item_product_unit_id" class="form-control input-sm">
                            <option value="0"><?php _trans('none'); ?></option>
                            <?php foreach ($units as $unit) { ?>
                                <option value="<?php echo $unit->unit_id; ?>"
                                    <?php check_select($item->item_product_unit_id, $unit->unit_id); ?>>
                                    <?php echo htmlsc($unit->unit_name) . "/" . htmlsc($unit->unit_name_plrl); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </td>


            </tr>
            </tbody>
        <?php } ?>
        </tbody>



    </table>
</div>

<br>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="btn-group">
            <?php if ($invoice->is_read_only != 1) { ?>
                <a href="#" class="btn_add_row btn btn-sm btn-default">
                    <i class="fa fa-plus"></i> <?php _trans('add_new_row'); ?>
                </a>
                <a href="#" class="btn_add_product btn btn-sm btn-default">
                    <i class="fa fa-database"></i>
                    <?php _trans('add_product'); ?>
                </a>
                <a href="#" class="btn_add_task btn btn-sm btn-default">
                    <i class="fa fa-database"></i> <?php _trans('add_task'); ?>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="col-xs-12 visible-xs visible-sm"><br></div>

    <div class="col-xs-12 col-md-6 col-md-offset-2 col-lg-4 col-lg-offset-4">
        <table class="table table-bordered text-right">
            <tr>
                <td style="width: 40%;">Cantidad de </td>
                <td style="width: 60%;"
                    class="amount" id="kg"></td>
            </tr>
            <tr style="display:none;">
                <td><?php _trans('item_tax'); ?></td>
                <td class="amount"><?php echo format_currency($invoice->invoice_item_tax_total); ?></td>
            </tr>
            <tr style="display:none;">
                <td><?php _trans('invoice_tax'); ?></td>
                <td>
                    <?php if ($invoice_tax_rates) {
                        foreach ($invoice_tax_rates as $invoice_tax_rate) { ?>
                            <form method="post"
                                action="<?php echo site_url('invoices/delete_invoice_tax/' . $invoice->invoice_id . '/' . $invoice_tax_rate->invoice_tax_rate_id) ?>">
                                <?php _csrf_field(); ?>
                                <span class="amount">
                                    <?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?>
                                </span>
                                <span class="text-muted">
                                    <?php echo htmlsc($invoice_tax_rate->invoice_tax_rate_name) . ' ' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) ?>
                                </span>
                                <button type="submit" class="btn btn-xs btn-link"
                                        onclick="return confirm('<?php _trans('delete_tax_warning'); ?>');">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        <?php }
                    } else {
                        echo format_currency('0');
                    } ?>
                </td>
            </tr>
            <tr style="display:none;">
                <td class="td-vert-middle"><?php _trans('discount'); ?></td>
                <td class="clearfix">
                    <div class="discount-field">
                        <div class="input-group input-group-sm">
                            <input id="invoice_discount_amount" name="invoice_discount_amount"
                                   class="discount-option form-control input-sm amount"
                                   value="<?php echo format_amount($invoice->invoice_discount_amount != 0 ? $invoice->invoice_discount_amount : ''); ?>"
                                <?php if ($invoice->is_read_only == 1) {
                                    echo 'disabled="disabled"';
                                } ?>>
                            <div class="input-group-addon"><?php echo get_setting('currency_symbol'); ?></div>
                        </div>
                    </div>
                    <div class="discount-field">
                        <div class="input-group input-group-sm">
                            <input id="invoice_discount_percent" name="invoice_discount_percent"
                                   value="<?php echo format_amount($invoice->invoice_discount_percent != 0 ? $invoice->invoice_discount_percent : ''); ?>"
                                   class="discount-option form-control input-sm amount"
                                <?php if ($invoice->is_read_only == 1) {
                                    echo 'disabled="disabled"';
                                } ?>>
                            <div class="input-group-addon">&percnt;</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><?php _trans('total'); ?></td>
                <td class="amount"><h3><?php echo format_currency($invoice->invoice_total); ?></h3></td>
            </tr>
            <tr>
                <td><?php _trans('paid'); ?></td>
                <td class="amount"><b><?php echo format_currency($invoice->invoice_paid); ?></b></td>
            </tr>
            <tr>
                <td><b><?php _trans('balance'); ?></b></td>
                <td class="amount"><b><?php echo format_currency($invoice->invoice_balance); ?></b></td>
            </tr>
        </table>
    </div>
</div>
</div>
<script>
$('#item_table').each(function(){
  var totalPoints = 0;
  $(this).find('td.td-quantity >input').each(function(){
    totalPoints += parseInt($(this).val());
  });
  $('<h3>'+totalPoints+'</h3>').appendTo("#kg");
});
</script>
