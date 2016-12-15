<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_rekap_mesin"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_inquiry_rekap_mesin">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_item_id'"       width="100"  align="center" halign="center" sortable="false">Kode</th>
            <th data-options="field:'m_item_name'"     width="100"  align="center" halign="center" sortable="false">Nama Mesin</th>
            <th data-options="field:'m_machine_lines'" width="100"  align="center" halign="center" sortable="false">Line</th>
            <th data-options="field:'m_machine_mac'"   width="100"  align="center" halign="center" sortable="false">Mesin</th>
            <th data-options="field:'box'"             width="100"  align="center" halign="center" sortable="false">Box</th>
            <th data-options="field:'kg'"              width="100"  align="center" halign="center" sortable="false">Kg</th>
            <th data-options="field:'t_process_qty'"   width="100"  align="center" halign="center" sortable="false">Pcs</th>
        </tr>
    </thead>    
</table>

<div id="dlg-inquiry_rekap_mesin_detail"></div>

<script type="text/javascript">
    var toolbar_inquiry_rekap_mesin = [{
        id      : 'inquiry_rekap-detail',
        text    : 'Detail',
        iconCls : 'icon-search-2',
        handler : function(){inquiryRekapDetail();}
    }];
    
    $('#grid-inquiry_rekap_mesin').datagrid({
        onLoadSuccess   : function(){
            $('#inquiry_rekap-detail').linkbutton('disable');
        },
        onSelect        : function(){
            $('#inquiry_rekap-detail').linkbutton('enable');
        },
        onClickRow      : function(){
           $('#inquiry_rekap-detail').linkbutton('enable');
        },
        url             : '<?php echo site_url('inquiry/rekap/showRekapMesin'); ?>?grid=true&rekap_proses='+rekap_proses+'&rekap_tgl_from='+rekap_tgl_from+'&rekap_tgl_to='+rekap_tgl_to});
        
    function inquiryRekapDetail(){            
        rowDetail = $('#grid-inquiry_rekap_mesin').datagrid('getSelected');
        if (rowDetail){
            var urlDetail     = '<?php echo site_url('inquiry/rekap/showRekapMesinDetail'); ?>?rekap_item='+rowDetail.m_item_id+'&rekap_mesin='+rowDetail.t_process_machine+'&rekap_proses='+rekap_proses+'&rekap_tgl_from='+rekap_tgl_from+'&rekap_tgl_to='+rekap_tgl_to;
            $('#dlg-inquiry_rekap_mesin_detail').dialog({
                title   : 'Detail '+rowDetail.m_item_name+' / Line: '+rowDetail.m_machine_lines+' Mesin: '+rowDetail.m_machine_mac,
                width   : 500,
                height  : 400,
                modal   : true,
                href    : urlDetail
            });
        }
    }
</script>

<!-- End of file v_rekap_mesin.php -->
<!-- Location: ./views/inquiry/rekap/v_rekap_mesin.php -->
