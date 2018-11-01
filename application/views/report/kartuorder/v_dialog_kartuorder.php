<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-dialog_report_kartuorder{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_report_kartuorder{
        margin:0;
        padding:10px 100px;
    }
    .ftitle{
        font-size:14px;
        font-weight:bold;
        padding:5px 0;
        margin-bottom:10px;
        border-bottom:1px solid #ccc;
    }
    .fitem{
        margin-bottom:5px;
    }
    .fitem label{
        display:inline-block;
        width:100px;
    }
</style>
<!-- Form -->
    <form id="fm-dialog_report_kartuorder" method="post" novalidate buttons="#dlg_btn-dialog_report_kartuorder">
        <div class="fitem">
            <label for="type">LOT</label>
            <input type="text" id="report_lot" name="report_lot" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('report/kartuorder/getLot'); ?>',
                method:'get', valueField:'t_po_detail_lot_no', textField:'t_po_detail_lot_no', panelHeight:'150'"/>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_report_kartuorder">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="cetak_kartuorder();">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>


<script type="text/javascript">
    function cetak_kartuorder(){
        var isValid = $('#fm-dialog_report_kartuorder').form('validate');
        report_lot     = $('#report_lot').combobox('getValue');
        if (isValid){            
            $('#dlg-report_kartuorder_lot').dialog({
                title   : 'LOT - '+report_lot,
                href    : '<?php echo site_url('report/kartuorder/lot'); ?>?nilailot='+report_lot,
                width   : 400,
                height  : 400,
                modal   : true,
                cache   : false,
                closed  : true
            });
            $('#dlg').dialog('close');
            $('#dlg-report_kartuorder_lot').dialog('open');
        }          
    }
    
</script>

<div id="dlg-report_kartuorder_lot">
    
</div>
<!-- End of file v_dialog_kartuorder.php -->
<!-- Location: ./views/report/kartuorder/v_dialog_kartuorder.php -->
