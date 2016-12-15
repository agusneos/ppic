<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_rekap_mesin_detail"
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
    $('#grid-inquiry_rekap_mesin_detail').datagrid({
        url             : '<?php echo site_url('inquiry/rekap/showRekapMesinDetail'); ?>?grid=true&rekap_item='+rowDetail.m_item_id+'&rekap_mesin='+rowDetail.t_process_machine+'&rekap_proses='+rekap_proses+'&rekap_tgl_from='+rekap_tgl_from+'&rekap_tgl_to='+rekap_tgl_to}
    );
</script>

<!-- End of file v_rekap_mesin_detail.php -->
<!-- Location: ./views/inquiry/rekap/v_rekap_mesin_detail.php -->
