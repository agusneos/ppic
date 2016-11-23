<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
        parser:function(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    });
</script>
<style type="text/css">
    #fm-dialog_prodexcel{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_prodexcel{
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
    <form id="fm-dialog_prodexcel" method="post" novalidate buttons="#dlg_btn-dialog_prodexcel">
        <div class="fitem">
            <label for="type">Proses</label>
            <input type="text" id="proses" name="proses" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('report/prodexcel/getProces'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Dari</label>
            <input  id="tglStart" name="tglStart" class="easyui-datetimebox" style="width:150px;" required>
        </div>
        <div class="fitem">
            <label for="type">Sampai</label>
            <input  id="tglEnd" name="tglEnd" class="easyui-datetimebox" style="width:150px;" required>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_prodexcel">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="cetak_prodexcel()">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Batal</a>
</div>

<script type="text/javascript">
    function cetak_prodexcel(){
        var isValid = $('#fm-dialog_prodexcel').form('validate');
        if (isValid){
            var proc    = $('#proses').combobox('getValue');
            var start   = $('#tglStart').datetimebox('getValue');
            var end     = $('#tglEnd').datetimebox('getValue');
            var url = '<?php echo site_url('report/prodexcel/exportExcel'); ?>?proc='+proc+'&start='+start+'&end='+end;
            window.open(url);
            $('#dlg').dialog('close');           
        }          
    }
</script>

<!-- End of file v_dialog_prodexcel.php -->
<!-- Location: ./views/report/hutang_supplier/v_dialog_prodexcel.php -->
