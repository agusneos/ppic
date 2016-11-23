<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-admin_group"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_admin_group">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'a_level_id'"    width="200" align="center" sortable="true">Kode Group</th>
            <th data-options="field:'a_level_name'"  width="400" halign="center" align="left" sortable="true">Nama Group</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_admin_group = [{
        id      : 'admin_group-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){adminGroupCreate();}
    },{
        id      : 'admin_group-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){adminGroupUpdate();}
    },{
        id      : 'admin_group-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){adminGroupHapus();}
    },{
        id      : 'admin_group-akses',
        text    : 'Akses Menu',
        iconCls : 'icon-menu',
        handler : function(){adminGroupAkses();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){adminGroupRefresh();}
    }];
    
    $('#grid-admin_group').datagrid({
        onLoadSuccess   : function(){
            $('#admin_group-edit').linkbutton('disable');
            $('#admin_group-delete').linkbutton('disable');
            $('#admin_group-akses').linkbutton('disable');
        },
        onSelect        : function(){
            adminGroupCheckAdmin();
        },
        onClickRow      : function(){
            adminGroupCheckAdmin();
        },
        onDblClickRow   : function(){
            adminGroupUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('admin/group/index'); ?>?grid=true'})
    .datagrid('enableFilter');
    
    function adminGroupCheckAdmin(){
        var row = $('#grid-admin_group').datagrid('getSelected');
        if(row.a_level_id==1){
            $('#admin_group-edit').linkbutton('disable');
            $('#admin_group-delete').linkbutton('disable');
            $('#admin_group-akses').linkbutton('disable');
        }
        else{
            $('#admin_group-edit').linkbutton('enable');
            $('#admin_group-delete').linkbutton('enable');
            $('#admin_group-akses').linkbutton('enable');
        }
    }
    
    function adminGroupRefresh() {
        $('#admin_group-edit').linkbutton('disable');
        $('#admin_group-delete').linkbutton('disable');
        $('#admin_group-akses').linkbutton('disable');
        $('#grid-admin_group').datagrid('reload');
    }
    
    function adminGroupCreate() {
        $('#dlg-admin_group').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-admin_group').form('clear');
        url = '<?php echo site_url('admin/group/create'); ?>';
    }
    
    function adminGroupUpdate() {
        var row = $('#grid-admin_group').datagrid('getSelected');
        if(row){
            $('#dlg-admin_group').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-admin_group').form('load',row);
            url = '<?php echo site_url('admin/group/update'); ?>/' + row.a_level_id;
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function adminGroupSave(){
        $('#fm-admin_group').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-admin_group').dialog('close');
                    adminGroupRefresh();
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
        
    function adminGroupHapus(){
        var row = $('#grid-admin_group').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Group '+row.a_level_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('admin/group/delete'); ?>',{a_level_id:row.a_level_id},function(result){
                        if (result.success){
                            adminGroupRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else{
                            var win = $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Dihapus !</div>'+result.error
                            });
                            win.window('window').addClass('bg-error');  
                        }
                    },'json');
                }
            });
            win.find('.messager-icon').removeClass('messager-question').addClass('messager-warning');
            win.window('window').addClass('bg-warning');
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function adminGroupAkses(){
        var row = $('#grid-admin_group').datagrid('getSelected');
        if(row){
            $('#dlg-admin_group-akses').dialog({
                title   : row.a_level_name,
                modal   : true
            }).dialog('open');
            $('#menutree').tree({
                url             : '<?php echo site_url('admin/group/menu'); ?>/' + row.a_level_id,
                cascadeCheck    : false,
                checkbox        : true,
                lines           : true,
                animate         : true,
                onCheck: function(node){
                    $.post('<?php echo site_url('admin/group/menu_update'); ?>',{a_group_id:node.groupId, a_group_status:node.checked},function(result){
                        if (result.success){
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Diubah</div>'
                            });
                        }
                        else{
                            var win = $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Diubah !</div>'+result.error
                            });
                            win.window('window').addClass('bg-error');
                        }
                    },'json');
                },
                onLoadSuccess: function(node, data){
                    $('#menutree').tree('expandAll');
                }
            });
        }
    }
    
</script>

<style type="text/css">
    #fm-admin_group{
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
</style>


<!-- ----------- -->
<div id="dlg-admin_group" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-admin_group">
    <form id="fm-admin_group" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Group</label>
            <input type="text" id="a_level_name" name="a_level_name" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-admin_group">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="adminGroupSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-admin_group').dialog('close');">Batal</a>
</div>

<!-- Akses Menu -->
<div id="dlg-admin_group-akses" class="easyui-dialog" style="width:400px; height:400px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-admin_group-akses">
    <ul id="menutree" style="padding:5px"></ul>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-admin_group-akses">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-admin_group-akses').dialog('close');">Tutup</a>
</div>
<!-- End of file v_group.php -->
<!-- Location: ./application/views/admin/v_group.php -->