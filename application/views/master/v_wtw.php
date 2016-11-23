<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_wtw"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_wtw">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_item_bom_cat'"       width="80"  align="center" halign="center" sortable="true">Kategori</th>
            <th data-options="field:'m_item_bom_id'"        width="80"  align="center" halign="center" sortable="true">Kode Barang</th>
            <th data-options="field:'m_item_bom_name'"      width="200" align="left"   halign="center" sortable="true">Nama Barang</th>
            <th data-options="field:'m_item_bom_qty'"       width="80"  align="right"  halign="center" sortable="true">Qty</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_master_wtw = [{
        id      : 'master_wtw-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterWtwCreate();}
    },{
        id      : 'master_wtw-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterWtwUpdate();}
    },{
        id      : 'master_wtw-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterWtwHapus();}
    },{
        id      : 'master_wtw-upload',
        text    : 'Upload',
        iconCls : 'icon-upload',
        handler : function(){masterWtwUpload();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterWtwRefresh();}
    }];
    
    $('#grid-master_wtw').datagrid({
        onLoadSuccess   : function(){
            $('#master_wtw-edit').linkbutton('disable');
            $('#master_wtw-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_wtw-edit').linkbutton('enable');
            $('#master_wtw-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_wtw-edit').linkbutton('enable');
            $('#master_wtw-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterWtwUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/wtw/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterWtwRefresh() {
        $('#master_wtw-edit').linkbutton('disable');
        $('#master_wtw-delete').linkbutton('disable');
        $('#grid-master_wtw').datagrid('reload');
    }
    
    function masterWtwCreate() {
        $('#dlg-master_wtw').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_wtw').form('clear');
        url = '<?php echo site_url('master/wtw/create'); ?>';
        $('#m_item_bom_id').numberbox('enable');
    }
    
    function masterWtwUpdate() {
        var row = $('#grid-master_wtw').datagrid('getSelected');
        if(row){
            $('#dlg-master_wtw').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_wtw').form('load',row);
            url = '<?php echo site_url('master/wtw/update'); ?>/' + row.m_item_bom_id;
            $('#m_item_bom_id').numberbox('disable');
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterWtwSave(){
        $('#fm-master_wtw').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-master_wtw').dialog('close');
                    masterWtwRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Disimpan</div>'
                    });
                }
                else
                {
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Disimpan !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }
        
    function masterWtwHapus(){
        var row = $('#grid-master_wtw').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Item \n'+row.m_item_bom_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/wtw/delete'); ?>',{m_item_bom_id:row.m_item_bom_id},function(result){
                        if (result.success)
                        {
                            masterWtwRefresh();
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
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }

    function masterWtwUpload()
    {
        $('#dlg-master_wtw-upload').dialog({modal: true}).dialog('open').dialog('setTitle','Upload File');
        $('#fm-master_wtw-upload').form('reset');
        urls = '<?php echo site_url('master/wtw/upload'); ?>/';
    }
    
    function masterWtwUploadSave()
    {
        $('#fm-master_wtw-upload').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success)
                {
                    $('#dlg-master_wtw-upload').dialog('close');
                    masterWtwRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : result.total + ' ' +result.ok + ' ' + result.ng
                    });
                } 
                else 
                {
                    $.messager.show({
                        title   : 'Error',
                        msg     : 'Upload Data Gagal'
                    });
                }
            }
        });
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
    #fm-master_wtw{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_wtw-upload{
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

<div id="dlg-master_wtw-upload" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-master_wtw-upload">
    <form id="fm-master_wtw-upload" method="post" enctype="multipart/form-data" novalidate>       
        <div class="fitem">
            <label for="type">File</label>
            <input id="fileb" name="fileb" class="easyui-filebox" required="true"/>
        </div> 
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-master_wtw-upload">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterWtwUploadSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_wtw-upload').dialog('close')">Batal</a>
</div>


<!-- ----------- -->
<div id="dlg-master_wtw" class="easyui-dialog" style="width:600px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_wtw">
    <form id="fm-master_wtw" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Kategori</label>
            <input type="text" id="m_item_bom_cat" name="m_item_bom_cat" style="width:150px;" class="easyui-combobox" required="true"
                   data-options="url:'<?php echo site_url('master/wtw/enumBomCat'); ?>',
                   method:'get', valueField:'data', textField:'data', panelHeight:'auto'" />
        </div>
        <div class="fitem">
            <label for="type">Kode Barang</label>
            <input type="text" id="m_item_bom_id" name="m_item_bom_id" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="m_item_bom_name" name="m_item_bom_name" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Quantity</label>
            <input type="text" id="m_item_bom_qty" name="m_item_bom_qty" class="easyui-numberbox" precision="2" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_wtw">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterWtwSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_wtw').dialog('close')">Batal</a>
</div>

<!-- End of file v_wtw.php -->
<!-- Location: ./application/views/master/v_wtw.php -->