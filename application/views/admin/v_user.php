<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-admin_user"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_admin_user">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'a_user_id'"       width="30" align="center" sortable="true">ID</th>
            <th data-options="field:'a_user_name'"     width="100" halign="center" sortable="true">Nama Lengkap</th>
            <th data-options="field:'a_user_username'" width="50" align="center" sortable="true">Username</th>
            <th data-options="field:'a_level_name'"    width="50" align="center" sortable="true">Level</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_admin_user = [{
        id      : 'admin_user-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){adminUserCreate();}
    },{
        id      : 'admin_user-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){adminUserUpdate();}
    },{
        id      : 'admin_user-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){adminUserHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){adminUserRefresh();}
    }];
    
    $('#grid-admin_user').datagrid({
        onLoadSuccess   : function(){
            $('#admin_user-edit').linkbutton('disable');
            $('#admin_user-delete').linkbutton('disable');
            $('#admin_user-reset').linkbutton('disable');
        },
        onSelect        : function(){
            adminUserCheckAdmin();
        },
        onClickRow      : function(){
            adminUserCheckAdmin();
        },
        onDblClickRow   : function(){
            adminUserUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('admin/user/index'); ?>?grid=true'})
    .datagrid('enableFilter');
    
    function adminUserCheckAdmin(){
        var row = $('#grid-admin_user').datagrid('getSelected');
        if(row.a_user_id==1){
            $('#admin_user-edit').linkbutton('disable');
            $('#admin_user-delete').linkbutton('disable');
            $('#admin_user-reset').linkbutton('disable');
        }
        else{
            $('#admin_user-edit').linkbutton('enable');
            $('#admin_user-delete').linkbutton('enable');
            $('#admin_user-reset').linkbutton('enable');
        }
    }
    
    function adminUserRefresh() {
        $('#admin_user-edit').linkbutton('disable');
        $('#admin_user-delete').linkbutton('disable');
        $('#admin_user-reset').linkbutton('disable');
        $('#grid-admin_user').datagrid('reload');
    }
    
    function adminUserCreate(){
        $('#dlg-admin_user').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-admin_user').form('clear');
        url = '<?php echo site_url('admin/user/create'); ?>';
        $('#a_user_password').passwordbox({required:true});
    }
    
    function adminUserUpdate() {
        var row = $('#grid-admin_user').datagrid('getSelected');
        if(row){
            $('#dlg-admin_user').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-admin_user').form('load',row);
            url = '<?php echo site_url('admin/user/update'); ?>/' + row.a_user_id;
            $('#a_user_password').passwordbox({required:false});
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function adminUserSave(){
        $('#fm-admin_user').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-admin_user').dialog('close');
                    adminUserRefresh();
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
    
    function adminUserHapus(){
        var row = $('#grid-admin_user').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus User '+row.a_user_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('admin/user/delete'); ?>',{a_user_id:row.a_user_id},function(result){
                        if (result.success){
                            adminUserRefresh();
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

</script>
<style type="text/css">
    #fm-admin_user{
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

<!-- Dialog Input Form -->
<div id="dlg-admin_user" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-admin_user">
    <form id="fm-admin_user" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Lengkap</label>
            <input type="text" id="a_user_name" name="a_user_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Username</label>
            <input type="text" id="a_user_username" name="a_user_username" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Password</label>
            <input id="a_user_password" name="a_user_password" class="easyui-passwordbox" />
        </div>
        <div class="fitem">
            <label for="type">Level</label>
            <input class="easyui-combobox" id="a_user_level" name="a_user_level" required="true" data-options="
                url : '<?php echo site_url('admin/user/getLevel'); ?>',
                method:'get', valueField:'a_level_id', textField:'a_level_name', panelHeight:'auto',
                onShowPanel: function(){
                    $('#a_user_level').combobox('reload');
            }">
        </div>
    </form>
</div>
<!-- Dialog Input Button -->
<div id="dlg_buttons-admin_user">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="adminUserSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-admin_user').dialog('close');">Batal</a>
</div>

<!-- End of file v_user.php -->
<!-- Location: ./application/views/admin/v_user.php -->