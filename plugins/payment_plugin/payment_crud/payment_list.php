<?php

function payment_list() {
    if( is_admin() ){
        if(isset($_GET['delete'])){
            payment_delete(($_GET['delete']));
        }
    } else{
        jal_log_client_view();
    }
    ?>
    <style>
        table { 
            border-collapse: collapse;
             
            }

        table, td, th {
          border: 1px solid black;
          padding: 20px;
          text-align: center;
        }
        table tbody img {
            width: 65px;
        }
    </style>

    <div class="wrap">
        <table>
            <thead>
            <tr>
              <th>Payment Method</th>
              <th>Min Deposit</th>
              <th>Max Deposit </th>
              <th>Deposit Fee % </th>
              <th>Deposit Processing Time </th>
              <th>Min Withdrawal</th>
              <th>Max Withdrawal</th>
              <th>Withdrawal Fee %</th>
              <th>Withdrawal Processing Time</th>
              <?php if( is_admin() ): ?>
                <th>Update</th>
                <th>Delete</th>
              <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'payment_list';
            $payments = $wpdb->get_results("SELECT * from $table_name");
            foreach ($payments as $payment) {
                ?>
                <tr>
                    <td>
                        <?php if($payment->payment_method): ?>
                            <img src="<?= $payment->payment_method;?>" alt="<?= basename($payment->payment_method); ?>">
                        <?php endif;?>
                    </td>
                    <td><?= $payment->min_deposit; ?></td>
                    <td><?= $payment->max_deposit; ?></td>
                    <td><?= $payment->deposit_fee; ?></td>
                    <td><?= $payment->deposit_processing_time; ?></td>
                    <td><?= $payment->min_withdrawal; ?></td>
                    <td><?= $payment->max_withdrawal; ?></td>
                    <td><?= $payment->withdrawal_fee; ?></td>
                    <td><?= $payment->withdrawal_processing_time; ?></td>
                    <?php if( is_admin() ): ?>
                        <td><a href="<?php echo admin_url('admin.php?page=payment_item&id=' . $payment->id); ?>">Update</a> </td>
                        <td><a href="<?php echo admin_url('admin.php?page=payment_listing&delete=' . $payment->id); ?>"> Delete</a></td>
                    <?php endif; ?>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
    <?php

}
add_shortcode('short_payment_list', 'payment_list');
?>