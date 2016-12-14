<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_mutasi"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_inquiry_mutasi">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'proses'"   width="100"  align="center" halign="center" sortable="false">Proses</th>
            <th data-options="field:'qty'"      width="100"  align="center" halign="center" sortable="false">Quantity</th>
        </tr>
    </thead>    
</table>

<div id="dlg-inquiry_mutasi_detail"></div>

<script type="text/javascript">
    var toolbar_inquiry_mutasi = [{
        id      : 'inquiry_mutasi-detail',
        text    : 'Detail',
        iconCls : 'icon-search-2',
        handler : function(){inquiryMutasiDetail();}
    }];
    
    $('#grid-inquiry_mutasi').datagrid({
        onLoadSuccess   : function(){
            $('#inquiry_mutasi-detail').linkbutton('disable');
        },
        onSelect        : function(){
            $('#inquiry_mutasi-detail').linkbutton('enable');
        },
        onClickRow      : function(){
           $('#inquiry_mutasi-detail').linkbutton('enable');
        },
        url             : '<?php echo site_url('inquiry/mutasi/showMutasi'); ?>?grid=true&lot='+mutasi_lot+'&item='+mutasi_item});
        
    function inquiryMutasiDetail(){            
        rowDetail = $('#grid-inquiry_mutasi').datagrid('getSelected');
        if (rowDetail){
            var urlDetail     = '<?php echo site_url('inquiry/mutasi/showMutasiDetail'); ?>?lot='+mutasi_lot+'&item='+mutasi_item+'&proc='+rowDetail.proc;
            $('#dlg-inquiry_mutasi_detail').dialog({
                title   : 'Detail Proses '+rowDetail.proses,
                width   : 500,
                height  : 400,
                modal   : true,
                href    : urlDetail
            });
        }
    }
</script>

<!-- End of file v_mutasi.php -->
<!-- Location: ./views/inquiry/hutang_supplier/v_mutasi.php -->
