<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_vendor"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_vendor">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_vend_id'"    width="100" align="center" sortable="true">Kode Pelanggan</th>
            <th data-options="field:'m_vend_name'"  width="300" halign="center" align="left" sortable="true">Nama Pelanggan</th>
            <th data-options="field:'m_vend_key'"   width="100" halign="center" align="center" sortable="true">Kata Kunci</th>
            <th data-options="field:'m_vend_addr'"  width="500" halign="center" align="left" sortable="true">Alamat</th>
            </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_vendor = [{
        id      : 'master_vendor-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterVendorCreate();}
    },{
        id      : 'master_vendor-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterVendorUpdate();}
    },{
        id      : 'master_vendor-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterVendorHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterVendorRefresh();}
    }];
    
    $('#grid-master_vendor').datagrid({
        onLoadSuccess   : function(){
            $('#master_vendor-edit').linkbutton('disable');
            $('#master_vendor-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_vendor-edit').linkbutton('enable');
            $('#master_vendor-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_vendor-edit').linkbutton('enable');
            $('#master_vendor-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterVendorUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/vendor/index'); ?>?grid=true'})
    .datagrid('enableFilter');
    
    function masterVendorRefresh() {
        $('#master_vendor-edit').linkbutton('disable');
        $('#master_vendor-delete').linkbutton('disable');
        $('#grid-master_vendor').datagrid('reload');
    }
    
    function masterVendorCreate() {
        $('#dlg-master_vendor').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_vendor').form('clear');
        url = '<?php echo site_url('master/vendor/create'); ?>';
        $('#m_vend_id').numberbox('enable');
    }
    
    function masterVendorUpdate() {
        var row = $('#grid-master_vendor').datagrid('getSelected');
        if(row){
            $('#dlg-master_vendor').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_vendor').form('load',row);
            url = '<?php echo site_url('master/vendor/update'); ?>/' + row.m_vend_id;
            $('#m_vend_id').numberbox('disable');
        }
    }
    
    function masterVendorSave(){
        $('#fm-master_vendor').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_vendor').dialog('close');
                    masterVendorRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Disimpan</div>'
                    });
                }
                else{
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Disimpan !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }
        
    function masterVendorHapus(){
        var row = $('#grid-master_vendor').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Vendor '+row.m_vend_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/vendor/delete'); ?>',{m_vend_id:row.m_vend_id},function(result){
                        if (result.success)
                        {
                            masterVendorRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else
                        {
                            $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Dihapus !</div>'+result.error
                            });
                        }
                    },'json');
                }
            });
            win.find('.messager-icon').removeClass('messager-question').addClass('messager-warning');
            win.window('window').addClass('bg-warning');
        }
    }
    
</script>

<style type="text/css">
    .bg-error{ 
        background: red;
    }
    .bg-error .panel-title{
        color:#fff;
    }
    .bg-warning{ 
        background: yellow;
    }
    .bg-warning .panel-title{
        color:#000;
    }
    #fm-master_vendor{
        margin:0;
        padding:10px 30px;
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
    .fitem input{
        display:inline-block;
        width:150px;
    }
</style>

<!-- ----------- -->
<div id="dlg-master_vendor" class="easyui-dialog" style="width:600px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_vendor">
    <form id="fm-master_vendor" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Kode Pelanggan</label>
            <input type="text" id="m_vend_id" name="m_vend_id" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Pelanggan</label>
            <input type="text" id="m_vend_name" name="m_vend_name" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Kata Kunci</label>
            <input type="text" id="m_vend_key" name="m_vend_key" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Alamat</label>
            <input type="text" id="m_vend_addr" name="m_vend_addr" multiline="true" style="width:350px;height:80px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_vendor">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterVendorSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_vendor').dialog('close');">Batal</a>
</div>

<!-- End of file v_vendor.php -->
<!-- Location: ./application/views/master/v_vendor.php -->