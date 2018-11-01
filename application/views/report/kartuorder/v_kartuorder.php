<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<table id="grid-report_kartuorder_lot"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_report_kartuorder_lot">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'t_prod_id'"        width="100"  align="center" halign="center" sortable="false">Id</th>
            <th data-options="field:'t_prod_lot'"       width="100"  align="center" halign="center" sortable="false">LOT</th>
            <th data-options="field:'t_prod_sublot'"    width="100"  align="center" halign="center" sortable="false">Sub LOT</th>
            <th data-options="field:'t_prod_card'"      width="100"  align="center" halign="center" sortable="false">Kartu</th>
            <th data-options="field:'t_prod_qty'"       width="100"  align="right"  halign="center" sortable="false">Quantity</th>
        </tr>
    </thead>    
</table>

<div id="dlg-report_kartuorder_card_print">
    
</div>

<script type="text/javascript">
    var toolbar_report_kartuorder_lot = [{
        id      : 'report_kartuorder_lot-print_all',
        text    : 'Print All',
        iconCls : 'icon-print',
        handler : function(){reportKartuorderLotPrintAll();}
    },{
        id      : 'report_kartuorder_lot-print_sublot',
        text    : 'Print SubLot',
        iconCls : 'icon-print',
        handler : function(){reportKartuorderLotPrintSublot();}
    },{
        id      : 'treport_kartuorder_lot-print_selected',
        text    : 'Print Selected',
        iconCls : 'icon-print',
        handler : function(){reportKartuorderLotPrintSelected();}
    }];

    $('#grid-report_kartuorder_lot').datagrid({
        view            :scrollview,
        url             :'<?php echo site_url('report/kartuorder/lot'); ?>?grid=true&nilailot='+report_lot,
        remoteFilter    :true})
    .datagrid('enableFilter');

    function reportKartuorderLotPrintAll() {
        var row = $('#grid-report_kartuorder_lot').datagrid('getSelected');
        if (row){
            var url = '<?php echo site_url('report/kartuorder/printAll'); ?>/' + row.t_prod_lot;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-report_kartuorder_card_print').dialog({
                title   : 'LOT : '+row.t_prod_lot,
                content : content,
                modal   : true,
                iconCls : 'icon-print',
                plain   : true,
                width   : '80%',
                height  : '80%'
            });
        }
        else{
            $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function reportKartuorderLotPrintSublot() {
        var row = $('#grid-report_kartuorder_lot').datagrid('getSelected');
        if (row){
            var url = '<?php echo site_url('report/kartuorder/printSublot'); ?>?lot='+row.t_prod_lot+'&sublot='+ row.t_prod_sublot;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-report_kartuorder_card_print').dialog({
                title   : 'LOT : '+row.t_prod_lot+' / SubLot : '+row.t_prod_sublot,
                content : content,
                modal   : true,
                iconCls : 'icon-print',
                plain   : true,
                width   : '80%',
                height  : '80%'
            });
        }
        else{
            $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function reportKartuorderLotPrintSelected() {
        var row = $('#grid-report_kartuorder_lot').datagrid('getSelected');
        if (row){
            var url = '<?php echo site_url('report/kartuorder/printSelected'); ?>/' + row.t_prod_id;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-report_kartuorder_card_print').dialog({
                title   : 'LOT : '+row.t_prod_lot+' / SubLot : '+row.t_prod_sublot+' / Card : '+row.t_prod_card,
                content : content,
                modal   : true,
                iconCls : 'icon-print',
                plain   : true,
                width   : '80%',
                height  : '80%'
            });
        }
        else{
            $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
</script>

<!-- End of file v_kartuorder.php -->
<!-- Location: ./views/report/kartuorder/v_kartuorder.php -->
