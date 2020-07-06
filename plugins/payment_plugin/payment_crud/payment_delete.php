
<?php

function payment_delete($id){
    echo "payment delete";
    global $wpdb;
    $table_name=$wpdb->prefix.'payment_list';
    $wpdb->delete(
        $table_name,
        array('id'=>$id)
    );
    echo "deleted";
}
