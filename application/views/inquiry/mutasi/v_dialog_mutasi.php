<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style type="text/css">
    #fm-dialog_mutasi{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_mutasi{
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
    <form id="fm-dialog_mutasi" method="post" novalidate buttons="#dlg_btn-dialog_mutasi">
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="mutasi_item" name="mutasi_item" style="width:250px;" class="easyui-combobox" required="true"
                data-options="
                method:'get', valueField:'m_item_id', textField:'m_item_name', 
                onShowPanel: function(){
                    var url = '<?php echo site_url('inquiry/mutasi/getItem'); ?>';
                    $('#mutasi_item').combobox('reload', url);
                },
                onSelect: function(rec){
                    var url = '<?php echo site_url('inquiry/mutasi/getLot'); ?>/'+rec.m_item_id;
                    $('#mutasi_lot').combobox('reload', url);
                },panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">LOT Barang</label>
            <input type="text" id="mutasi_lot" name="mutasi_lot" style="width:150px;" class="easyui-combobox" required="true"
                data-options="valueField:'t_po_detail_lot_no', textField:'t_po_detail_lot_no', panelHeight:'150'"/>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_mutasi">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="show_mutasi();">Cetak</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>

<script type="text/javascript">
    function show_mutasi(){
        var isValid = $('#fm-dialog_mutasi').form('validate');
        if (isValid){
            lot         = $('#mutasi_lot').combobox('getValue');
            var url     = '<?php echo site_url('inquiry/mutasi/showMutasi'); ?>?lot='+lot;
            var title   = 'Mutasi WIP';

            $('#tt').tabs('close', title);
            $('#tt').tabs('add',{
                title   : title,                    
                href    : url,
                closable: true,
                iconCls : 'icon-search-2'
            });
            $('#dlg').dialog('close');
        }          
    }
</script>

<!-- End of file v_dialog_mutasi.php -->
<!-- Location: ./views/inquiry/hutang_supplier/v_dialog_mutasi.php -->
