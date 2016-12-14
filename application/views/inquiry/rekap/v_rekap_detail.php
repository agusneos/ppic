<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_rekap_detail"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'t_prod_lot'"       width="80"  align="center" halign="center" sortable="false">LOT</th>
            <th data-options="field:'t_prod_sublot'"    width="50"   align="center" halign="center" sortable="false">Sub LOT</th>
            <th data-options="field:'t_prod_card'"      width="50"   align="center" halign="center" sortable="false">Kartu</th>
            <th data-options="field:'t_process_qty'"    width="80"  align="center" halign="center" sortable="false">Quantity</th>
            <th data-options="field:'t_process_time'"   width="100"  align="center" halign="center" sortable="false">Time</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">    
    $('#grid-inquiry_rekap_detail').datagrid({
        url             : '<?php echo site_url('inquiry/rekap/showRekapDetail'); ?>?grid=true&lot='+rekap_lot+'&item='+rekap_item+'&proc='+rowDetail.proc}
    );
</script>

<!-- End of file v_rekap_detail.php -->
<!-- Location: ./views/inquiry/hutang_supplier/v_rekap_detail.php -->
