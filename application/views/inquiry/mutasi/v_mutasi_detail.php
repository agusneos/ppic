<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_mutasi_detail"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'t_prod_lot'"       width="100"  align="center" halign="center" sortable="false">LOT</th>
            <th data-options="field:'t_prod_sublot'"    width="100"  align="center" halign="center" sortable="false">Sub LOT</th>
            <th data-options="field:'t_prod_card'"      width="100"  align="center" halign="center" sortable="false">Kartu</th>
            <th data-options="field:'t_process_qty'"    width="100"  align="center" halign="center" sortable="false">Quantity</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">    
    $('#grid-inquiry_mutasi_detail').datagrid({
        url             : '<?php echo site_url('inquiry/mutasi/showMutasiDetail'); ?>?grid=true&lot='+lot+'&proc='+rowDetail.proc}
    );
</script>

<!-- End of file v_mutasi_detail.php -->
<!-- Location: ./views/inquiry/hutang_supplier/v_mutasi_detail.php -->
