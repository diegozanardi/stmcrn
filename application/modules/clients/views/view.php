<script>
    $(function () {
        $('#save_client_note').click(function () {
            $.post('<?php echo site_url('clients/ajax/save_client_note'); ?>',
                {
                    client_id: $('#client_id').val(),
                    client_note: $('#client_note').val()
                }, function (data) {
                    <?php echo(IP_DEBUG ? 'console.log(data);' : ''); ?>
                    var response = JSON.parse(data);
                    if (response.success === 1) {
                        // The validation was successful
                        $('.control-group').removeClass('error');
                        $('#client_note').val('');

                        // Reload all notes
                        $('#notes_list').load("<?php echo site_url('clients/ajax/load_client_notes'); ?>",
                            {
                                client_id: <?php echo $client->client_id; ?>
                            }, function (response) {
                                <?php echo(IP_DEBUG ? 'console.log(response);' : ''); ?>
                            });
                    } else {
                        // The validation was not successful
                        $('.control-group').removeClass('error');
                        for (var key in response.validation_errors) {
                            $('#' + key).parent().addClass('has-error');
                        }
                    }
                });
        });
    });
</script>

<?php
$locations = array();
foreach ($custom_fields as $custom_field) {
    if (array_key_exists($custom_field->custom_field_location, $locations)) {
        $locations[$custom_field->custom_field_location] += 1;
    } else {
        $locations[$custom_field->custom_field_location] = 1;
    }
}
?>

<div id="headerbar">
    <h1 class="headerbar-title"><?php _htmlsc(format_client($client)); ?></h1>

    <div class="headerbar-item pull-right">
        <div class="btn-group btn-group-sm">
            <a href="#" class="btn btn-default client-create-quote"
               data-client-id="<?php echo $client->client_id; ?>">
                <i class="fa fa-file"></i> <?php _trans('create_quote'); ?>
            </a>
            <a href="#" class="btn btn-default client-create-invoice"
               data-client-id="<?php echo $client->client_id; ?>">
                <i class="fa fa-file-text"></i> <?php _trans('create_invoice'); ?></a>
            <a href="<?php echo site_url('clients/form/' . $client->client_id); ?>"
               class="btn btn-default">
                <i class="fa fa-edit"></i> <?php _trans('edit'); ?>
            </a>
            <a class="btn btn-danger"
               href="<?php echo site_url('clients/delete/' . $client->client_id); ?>"
               onclick="return confirm('<?php _trans('delete_client_warning'); ?>');">
                <i class="fa fa-trash-o"></i> <?php _trans('delete'); ?>
            </a>
        </div>
    </div>

</div>

<ul id="submenu" class="nav nav-tabs nav-tabs-noborder">
    <li class="active"><a data-toggle="tab" href="#clientDetails"><?php _trans('details'); ?></a></li>
      <li><a data-toggle="tab" href="#clientInvoices"><?php _trans('invoices'); ?></a></li>
    <li><a data-toggle="tab" href="#clientPayments"><?php _trans('payments'); ?></a></li>

</ul>

<div id="content" class="tabbable tabs-below no-padding">
    <div class="tab-content no-padding">

        <div id="clientDetails" class="tab-pane tab-rich-content active">

            <?php $this->layout->load_view('layout/alerts'); ?>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">

                    <h3><?php _htmlsc(format_client($client)); ?></h3>
                    <p>
                        <?php $this->layout->load_view('clients/partial_client_address'); ?>
                    </p>
                    <span>
                    <?php if ($client->client_vat_id) : ?>

                            <?php _trans('vat_id'); ?>
                            <?php _htmlsc($client->client_vat_id); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($client->client_tax_code) : ?>
                        <p>
                          <?php _trans('tax_code'); ?></th>
                          <?php _htmlsc($client->client_tax_code); ?>
                        </p>
                    <?php endif; ?>

                    <?php foreach ($custom_fields as $custom_field) : ?>
                        <?php if ($custom_field->custom_field_location != 4) {
                            continue;
                        } ?>
                        <tr>
                            <?php
                            $column = $custom_field->custom_field_label;
                            $value = $this->mdl_client_custom->form_value('cf_' . $custom_field->custom_field_id);
                            ?>
                            <th><?php _htmlsc($column); ?></th>
                            <td><?php _htmlsc($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </div>

            </div>


            <div class="row">
                <div class="col-xs-12 col-md-6">

                    <div class="panel panel-default no-margin">
                        <div class="panel-heading"><?php _trans('contact_information'); ?></div>
                        <div class="panel-body table-content">
                            <table class="table no-margin">
                                <?php if ($client->client_email) : ?>
                                    <tr>
                                        <th><?php _trans('email'); ?></th>
                                        <td><?php _auto_link($client->client_email, 'email'); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($client->client_phone) : ?>
                                    <tr>
                                        <th><?php _trans('phone'); ?></th>
                                        <td><?php _htmlsc($client->client_phone); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($client->client_mobile) : ?>
                                    <tr>
                                        <th><?php _trans('mobile'); ?></th>
                                        <td><?php _htmlsc($client->client_mobile); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($client->client_fax) : ?>
                                    <tr>
                                        <th><?php _trans('fax'); ?></th>
                                        <td><?php _htmlsc($client->client_fax); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($client->client_web) : ?>
                                    <tr>
                                        <th><?php _trans('web'); ?></th>
                                        <td><?php _auto_link($client->client_web, 'url', true); ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($custom_fields as $custom_field) : ?>
                                    <?php if ($custom_field->custom_field_location != 2) {
                                        continue;
                                    } ?>
                                    <tr>
                                        <?php
                                        $column = $custom_field->custom_field_label;
                                        $value = $this->mdl_client_custom->form_value('cf_' . $custom_field->custom_field_id);
                                        ?>
                                        <th><?php _htmlsc($column); ?></th>
                                        <td><?php _htmlsc($value); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

            <?php if ($client->client_surname != ""): //Client is not a company ?>
                <hr>

                <div class="row">
                    <div class="col-xs-12 col-md-6 hide">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php _trans('personal_information'); ?>
                            </div>

                            <div class="panel-body table-content">
                                <table class="table no-margin">
                                    <tr>
                                        <th><?php _trans('birthdate'); ?></th>
                                        <td><?php echo format_date($client->client_birthdate); ?></td>
                                    </tr>
                                    <tr>
                                        <th><?php _trans('gender'); ?></th>
                                        <td><?php echo format_gender($client->client_gender) ?></td>
                                    </tr>
                                    <?php if ($this->mdl_settings->setting('sumex') == '1'): ?>
                                        <tr>
                                            <th><?php _trans('sumex_ssn'); ?></th>
                                            <td><?php echo format_avs($client->client_avs) ?></td>
                                        </tr>

                                        <tr>
                                            <th><?php _trans('sumex_insurednumber'); ?></th>
                                            <td><?php _htmlsc($client->client_insurednumber) ?></td>
                                        </tr>

                                        <tr>
                                            <th><?php _trans('sumex_veka'); ?></th>
                                            <td><?php _htmlsc($client->client_veka) ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($custom_fields as $custom_field) : ?>
                                        <?php if ($custom_field->custom_field_location != 3) {
                                            continue;
                                        } ?>
                                        <tr>
                                            <?php
                                            $column = $custom_field->custom_field_label;
                                            $value = $this->mdl_client_custom->form_value('cf_' . $custom_field->custom_field_id);
                                            ?>
                                            <th><?php _htmlsc($column); ?></th>
                                            <td><?php _htmlsc($value); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <?php
            if ($custom_fields) : ?>
                <hr>

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="panel panel-default no-margin">

                            <div class="panel-heading">
                                <?php _trans('custom_fields'); ?>
                            </div>
                            <div class="panel-body table-content">
                                <table class="table no-margin">
                                    <?php foreach ($custom_fields as $custom_field) : ?>
                                        <?php if ($custom_field->custom_field_location != 0) {
                                            continue;
                                        } ?>
                                        <tr>
                                            <?php
                                            $column = $custom_field->custom_field_label;
                                            $value = $this->mdl_client_custom->form_value('cf_' . $custom_field->custom_field_id);
                                            ?>
                                            <th><?php _htmlsc($column); ?></th>
                                            <td><?php _htmlsc($value); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6">
                      <div class="widget">
                        <div class="widget-heading clearfix">
                          <div class="pull-left">Facturado</div>
                        </div>
                        <div class="widget-body clearfix">
                          <div class="pull-left">
                          <i class="fa fa-file-text-o"></i>
                          </div>
                          <div class="pull-right number"><?php echo format_currency($client->client_invoice_total); ?></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                      <div class="widget">
                        <div class="widget-heading clearfix">
                          <div class="pull-left">Cobrado</div>
                        </div>
                        <div class="widget-body clearfix">
                          <div class="pull-left">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <div class="pull-right number">  <?php echo format_currency($client->client_invoice_paid); ?></div>
                        </div>

                      </div>

                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                      <div class="widget">
                        <div class="widget-heading clearfix">
                          <div class="pull-left">Saldo</div>
                        </div>
                        <div class="widget-body clearfix">
                          <div class="pull-left">
                            <i class="fa fa-balance-scale"></i>
                          </div>
                          <div class="pull-right number">  <?php echo format_currency($client->client_invoice_balance); ?></div>
                        </div>

                      </div>

                    </div>
                  </div>
                  <h2>Cuenta Corriente</h2>
                  <div class="table-responsive-sm">
                      <table class="table table-bordered table-hover">

                          <thead>
                          <tr>
                              <th>Fecha</th>
                              <th>Comprobante</th>
                              <th>Facturado</th>
                              <th>Pagado</th>
                              <th><?php _trans('balance'); ?></th>
                              <th><?php _trans('options'); ?></th>
                          </tr>
                          </thead>

                          <tbody>
                          <?php
                          $invoice_idx = 1;
                          $invoice_count = count($invoices);
                          $invoice_list_split = $invoice_count > 3 ? $invoice_count / 2 : 9999;
                          foreach ($invoices as $invoice) {
                              // Disable read-only if not applicable
                              if ($this->config->item('disable_read_only') == true) {
                                  $invoice->is_read_only = 0;
                              }
                              // Convert the dropdown menu to a dropup if invoice is after the invoice split
                              $dropup = $invoice_idx > $invoice_list_split ? true : false;
                              ?>
                              <tr>
                                <td>
                                    <?php echo date_from_mysql($invoice->invoice_date_created); ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('invoices/view/' . $invoice->invoice_id); ?>"
                                       title="<?php _trans('edit'); ?>">
                                        <?php echo($invoice->invoice_number ? $invoice->invoice_number : $invoice->invoice_id); ?>
                                    </a>
                                </td>
                                <td class=" <?php if ($invoice->invoice_sign == '-1') {
                                    echo 'text-danger';
                                }; ?>">
                                    <?php echo format_currency($invoice->invoice_total); ?>
                                </td>
                                <td>


                                </td>
                                <td>
                                    <?php echo format_currency($invoice->invoice_balance); ?>
                                </td>
                                <td>
                                      <div class="options btn-group<?php echo $dropup ? ' dropup' : ''; ?>">
                                          <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                              <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                                          </a>
                                          <ul class="dropdown-menu">
                                              <?php if ($invoice->is_read_only != 1) { ?>
                                                  <li>
                                                      <a href="<?php echo site_url('invoices/view/' . $invoice->invoice_id); ?>">
                                                          <i class="fa fa-edit fa-margin"></i> <?php _trans('edit'); ?>
                                                      </a>
                                                  </li>
                                              <?php } ?>
                                              <li>
                                                  <a href="<?php echo site_url('invoices/generate_pdf/' . $invoice->invoice_id); ?>"
                                                     target="_blank">
                                                      <i class="fa fa-print fa-margin"></i> <?php _trans('download_pdf'); ?>
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="<?php echo site_url('mailer/invoice/' . $invoice->invoice_id); ?>">
                                                      <i class="fa fa-send fa-margin"></i> <?php _trans('send_email'); ?>
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="#" class="invoice-add-payment"
                                                     data-invoice-id="<?php echo $invoice->invoice_id; ?>"
                                                     data-invoice-balance="<?php echo $invoice->invoice_balance; ?>"
                                                     data-invoice-payment-method="<?php echo $invoice->payment_method; ?>">
                                                      <i class="fa fa-money fa-margin"></i>
                                                      <?php _trans('enter_payment'); ?>
                                                  </a>
                                              </li>
                                              <?php if (
                                                  $invoice->invoice_status_id == 1 ||
                                                  ($this->config->item('enable_invoice_deletion') === true && $invoice->is_read_only != 1)
                                              ) { ?>
                                                  <li>
                                                      <form action="<?php echo site_url('invoices/delete/' . $invoice->invoice_id); ?>"
                                                            method="POST">
                                                          <?php _csrf_field(); ?>
                                                          <button type="submit" class="dropdown-button"
                                                                  onclick="return confirm('<?php _trans('delete_invoice_warning'); ?>');">
                                                              <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
                                                          </button>
                                                      </form>
                                                  </li>
                                              <?php } ?>
                                          </ul>
                                      </div>
                                  </td>
                              </tr>
                              <?php
                              $invoice_idx++;
                          } ?>


                          <?php foreach ($payments as $payment) { ?>
                              <tr>
                                  <td><?php echo date_from_mysql($payment->payment_date); ?></td>
                                  <td><?php _htmlsc($payment->payment_method_name); ?></td>
                                    <td><span class="badge"><?php _htmlsc($payment->payment_note); ?></span></td>
                                  <td><?php echo format_currency($payment->payment_amount); ?></td>
                                  <td></td>

                                  <td>
                                      <div class="options btn-group">
                                          <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                              <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                                          </a>
                                          <ul class="dropdown-menu">
                                              <li>
                                                  <a href="<?php echo site_url('payments/form/' . $payment->payment_id); ?>">
                                                      <i class="fa fa-edit fa-margin"></i>
                                                      <?php _trans('edit'); ?>
                                                  </a>
                                              </li>
                                              <li>
                                                  <form action="<?php echo site_url('payments/delete/' . $payment->payment_id); ?>"
                                                        method="POST">
                                                      <?php _csrf_field(); ?>
                                                      <button type="submit" class="dropdown-button"
                                                              onclick="return confirm('<?php _trans('delete_record_warning'); ?>');">
                                                          <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
                                                      </button>
                                                  </form>
                                              </li>
                                          </ul>
                                      </div>
                                  </td>
                              </tr>
                          <?php } ?>
                          </tbody>

                      </table>
                  </div>

                </div>
            </div>

        </div>



        <div id="clientInvoices" class="tab-pane table-content">
            <?php echo $invoice_table; ?>
        </div>

        <div id="clientPayments" class="tab-pane table-content">
            <?php echo $payment_table; ?>
        </div>
        
    </div>

</div>
