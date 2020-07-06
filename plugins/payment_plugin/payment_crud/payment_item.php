<?php

function payment_item()
{
    if( isset($_GET['id'])){
        global $wpdb;
        $id = $_GET['id'];
        $table_name = $wpdb->prefix . 'payment_list';
        $payments = $wpdb->get_results("SELECT * from $table_name where id = $id");
        $payment_method                = $payments[0]->payment_method;
        $min_deposit                   = $payments[0]->min_deposit;
        $max_deposit                   = $payments[0]->max_deposit;
        $deposit_fee                   = $payments[0]->deposit_fee;
        $deposit_processing_time       = $payments[0]->deposit_processing_time;
        $min_withdrawal                = $payments[0]->min_withdrawal;
        $max_withdrawal                = $payments[0]->max_withdrawal;
        $withdrawal_fee                = $payments[0]->withdrawal_fee;
        $withdrawal_processing_time    = $payments[0]->withdrawal_processing_time;    
        $button_text = "Update";
        $action_name = 'update';
    } else{
        $payment_method                = '';
        $min_deposit                   = '';
        $max_deposit                   = '';
        $deposit_fee                   = '';
        $deposit_processing_time       = '';
        $min_withdrawal                = '';
        $max_withdrawal                = '';
        $withdrawal_fee                = '';
        $withdrawal_processing_time    = '';
        $button_text = "Insert";
        $action_name = 'insert';
    }

    $image_url = '';
    //upload image
    if(isset($_FILES['payment_method']) && $_FILES['payment_method'] != ''){
        $attachment_id = media_handle_upload( 'payment_method', 0 );
        if( is_wp_error($attachment_id) ){
        }else{
            $image_url = wp_get_attachment_url($attachment_id);
        }
    }        
    
    ?>
    <table>
        <thead>
        <tr>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <form name="frm" action="#" method="post" enctype="multipart/form-data">
        <tr>
            <td>Payment Method:</td>
            <td>
            <?php if( $payment_method ): ?>
                <img src="<?php echo $payment_method;?>" width="150"/>
            <?php endif; ?>
            <input 
                type="file" 
                name="payment_method"
                value=""
            /></td>
        </tr>
        <tr>
            <td>Min Deposit</td>
            <td><input 
                type="text" 
                name="min_deposit"
                value="<?php echo $min_deposit;?>"
            /></td>
        </tr>
        <tr>
            <td>Max Deposit</td>
            <td><input 
                type="text" 
                name="max_deposit"
                value="<?php echo $max_deposit;?>"
            /></td>
        </tr>
        <tr>
            <td>Deposit Fee %</td>
            <td><input 
                type="text" 
                name="deposit_fee"
                value="<?php echo $deposit_fee;?>"
           /></td>
        </tr>
        <tr>
            <td>Deposit Processing Time</td>
            <td><input 
                type="text" 
                name="deposit_processing_time"
                value="<?php echo $deposit_processing_time;?>"
            /></td>
        </tr>
        <tr>
            <td>Min Withdrawal</td>
            <td><input 
                type="text" 
                name="min_withdrawal"
                value="<?php echo $min_withdrawal;?>"
            /></td>
        </tr>
        <tr>
            <td>Max Withdrawal</td>
            <td><input 
                type="text" 
                name="max_withdrawal"
                value="<?php echo $max_withdrawal;?>"
            /></td>
        </tr>
        <tr>
            <td>Withdrawal Fee %</td>
            <td><input 
                type="text" 
                name="withdrawal_fee"
                value="<?php echo $withdrawal_fee;?>"
            /></td>
        </tr>
        <tr>
            <td>Withdrawal Processing Time</td>
            <td>
                <input 
                type="text" 
                name="withdrawal_processing_time"
                value="<?php echo $withdrawal_processing_time;?>"
            /></td>
        </tr>
        <tr>
            <td>
                <input type="hidden" value="1" name="<?php echo $action_name;?>"/>
            </td>
            <td><input type="submit" value="<?php echo $button_text;?>" name="submit"/></td>
        </tr>
        </form>
        </tbody>
    </table>

<?php

if(isset($_POST['insert'])){
    global $wpdb;
    $payment_method                = $image_url;
    $min_deposit                   = $_POST['min_deposit'];
    $max_deposit                   = $_POST['max_deposit'];
    $deposit_fee                   = $_POST['deposit_fee'];
    $deposit_processing_time       = $_POST['deposit_processing_time'];
    $min_withdrawal                = $_POST['min_withdrawal'];
    $max_withdrawal                = $_POST['max_withdrawal'];
    $withdrawal_fee                = $_POST['withdrawal_fee'];
    $withdrawal_processing_time    = $_POST['withdrawal_processing_time'];
    $table_name = $wpdb->prefix . 'payment_list';

    $wpdb->insert(
        $table_name,
        array(
          'payment_method'             => $payment_method, 
          'min_Deposit'                => $min_deposit,
          'max_deposit'                => $max_deposit,
          'deposit_fee'                => $deposit_fee,
          'deposit_processing_time'    => $deposit_processing_time,
          'min_withdrawal'             => $min_withdrawal,
          'max_withdrawal'             => $max_withdrawal,
          'withdrawal_fee'             => $withdrawal_fee,
          'withdrawal_processing_time' => $withdrawal_processing_time
        )
    );
    echo "inserted";


    ?>
    <meta http-equiv="refresh" content="1; url=http://paymentmethod.local/wp-admin/admin.php?page=payment_listing"/>

    <?php
    exit;
}

    if(isset($_POST['update'])){
        global $wpdb;
        $payment_method                = $image_url;
        $min_deposit                   = $_POST['min_deposit'];
        $max_deposit                   = $_POST['max_deposit'];
        $deposit_fee                   = $_POST['deposit_fee'];
        $deposit_processing_time       = $_POST['deposit_processing_time'];
        $min_withdrawal                = $_POST['min_withdrawal'];
        $max_withdrawal                = $_POST['max_withdrawal'];
        $withdrawal_fee                = $_POST['withdrawal_fee'];
        $withdrawal_processing_time    = $_POST['withdrawal_processing_time'];
        $table_name = $wpdb->prefix . 'payment_list';

        $update_args = array(
            'min_deposit'                => $min_deposit,
            'max_deposit'                => $max_deposit,
            'deposit_fee'                => $deposit_fee,
            'deposit_processing_time'    => $deposit_processing_time,
            'min_withdrawal'             => $min_withdrawal,
            'max_withdrawal'                => $max_withdrawal,
            'withdrawal_fee'             => $withdrawal_fee,
            'withdrawal_processing_time' => $withdrawal_processing_time
        );

        if( $payment_method ){
            $update_args['payment_method'] = $payment_method;
        }

        $wpdb->update(
            $table_name,
            $update_args,
            array(
                'id' => $id
            )
        );
    
        ?>
        <meta http-equiv="refresh" content="1; url=http://paymentmethod.local/wp-admin/admin.php?page=payment_listing"/>

        <?php
        exit;
    }
}
