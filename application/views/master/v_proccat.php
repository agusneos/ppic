<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_proccat"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_master_proccat">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_process_cat_id'"            width="100" align="center" halign="center" sortable="true">Kode Proses</th>
            <th data-options="field:'m_process_cat_name'"          width="200" align="left"   halign="center" sortable="true">Nama Proses</th>
            <th data-options="field:'m_process_cat_table'"         width="200" align="left"   halign="center" sortable="true">Nama Tabel Proses</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_master_proccat = [{
        id      : 'master_proccat-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterProccatCreate();}
    },{
        id      : 'master_proccat-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterProccatUpdate();}
    },{
        id      : 'master_proccat-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterProccatHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterProccatRefresh();}
    }];
    
    $('#grid-master_proccat').datagrid({
        onLoadSuccess   : function(){
            $('#master_proccat-delete').linkbutton('disable');
            $('#master_proccat-edit').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_proccat-delete').linkbutton('enable');
            $('#master_proccat-edit').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_proccat-delete').linkbutton('enable');
            $('#master_proccat-edit').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterProccatUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/proccat/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterProccatRefresh() {
        $('#master_proccat-delete').linkbutton('disable');
        $('#master_proccat-edit').linkbutton('disable');
        $('#grid-master_proccat').datagrid('reload');
    }
    
    function masterProccatCreate() {
        $('#dlg-master_proccat').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_proccat').form('clear');
        url = '<?php echo site_url('master/proccat/create'); ?>';
    }
    
    function masterProccatUpdate(){
        var row = $('#grid-master_proccat').datagrid('getSelected');
        if(row){
            $('#dlg-master_proccat').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_proccat').form('load',row);
            url = '<?php echo site_url('master/proccat/update'); ?>/' + row.m_process_cat_id;
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterProccatSave(){
        $('#fm-master_proccat').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-master_proccat').dialog('close');
                    masterProccatRefresh();
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
        
    function masterProccatHapus(){
        var row = $('#grid-master_proccat').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Proses \n'+row.m_process_cat_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/proccat/delete'); ?>',{m_process_cat_name:row.m_process_cat_name},function(result){
                        if (result.success)
                        {
                            masterProccatRefresh();
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
    #fm-master_proccat{
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
    .fproccat{
        margin-bottom:5px;
    }
    .fproccat label{
        display:inline-block;
        width:100px;
    }
    .fproccat input{
        display:inline-block;
        width:150px;
    }
</style>

<!-- ----------- -->
<div id="dlg-master_proccat" class="easyui-dialog" style="width:450px; height:200px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_proccat">
    <form id="fm-master_proccat" method="post" novalidate>        
        <div class="fproccat">
            <label for="type">Nama Proses</label>
            <input type="text" id="m_process_cat_name" name="m_process_cat_name" style="width:150px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fproccat">
            <label for="type">Nama Tabel</label>
            <input type="text" id="m_process_cat_table" name="m_process_cat_table" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/proccat/getItemBom'); ?>',
                method:'get', valueField:'TABLE_NAME', textField:'TABLE_NAME',panelHeight:'150'"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_proccat">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterProccatSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_proccat').dialog('close')">Batal</a>
</div>

<!-- End of file v_proccat.php -->
<!-- Location: ./application/views/master/v_proccat.php -->