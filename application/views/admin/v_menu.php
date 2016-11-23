<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-admin_menu"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_admin_menu">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'a.a_menu_id'"        width="30" align="center" sortable="true">ID</th>            
            <th data-options="field:'b.a_menu_name'"      width="50" align="center" sortable="true">Parent Menu</th>
            <th data-options="field:'a.a_menu_name'"      width="100" halign="center" sortable="true">Nama Menu</th>
            <th data-options="field:'a.a_menu_uri'"       width="100" halign="center" sortable="true">URI</th>
            <th data-options="field:'a.a_menu_iconCls'"   width="100" halign="center" sortable="true">Icon</th>
            <th data-options="field:'a.a_menu_type'"      width="30" halign="center" sortable="true">Type</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_admin_menu = [{
        id      : 'admin_menu-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){adminMenuCreate();}
    },{
        id      : 'admin_menu-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){adminMenuUpdate();}
    },{
        id      : 'admin_menu-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){adminMenuHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){adminMenuRefresh();}
    }];
    
    $('#grid-admin_menu').datagrid({
        onLoadSuccess   : function(){
            $('#admin_menu-edit').linkbutton('disable');
            $('#admin_menu-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#admin_menu-edit').linkbutton('enable');
            $('#admin_menu-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#admin_menu-edit').linkbutton('enable');
            $('#admin_menu-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            adminMenuUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('admin/menu/index'); ?>?grid=true'})
    .datagrid('enableFilter');
    
    function adminMenuRefresh() {
        $('#admin_menu-edit').linkbutton('disable');
        $('#admin_menu-delete').linkbutton('disable');
        $('#grid-admin_menu').datagrid('reload');
    }
    
    function adminMenuCreate(){
        $('#dlg-admin_menu').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-admin_menu').form('clear');
        url = '<?php echo site_url('admin/menu/create'); ?>';
    }

    function adminMenuUpdate(){
        var row = $('#grid-admin_menu').datagrid('getSelected');
        if(row){
            $('#dlg-admin_menu').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-admin_menu').form('load',row);
            url = '<?php echo site_url('admin/menu/update'); ?>/' + row['a.a_menu_id'];
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }

    function adminMenuSave(){
        $('#fm-admin_menu').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-admin_menu').dialog('close');
                    adminMenuRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Disimpan</div>'
                    });
                } else {
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Disimpan !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }

    function adminMenuHapus(){
        var row = $('#grid-admin_menu').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Menu Id '+row['a.a_menu_id']+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('admin/menu/delete'); ?>',{a_menu_id:row['a.a_menu_id']},function(result){
                        if (result.success){
                            adminMenuRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else {
                            var win = $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Dihapus !</div>'+result.error
                            });
                            win.window('window').addClass('bg-error');
                        }
                    },'json');
                }
            });
        }
        else{
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
    #fm-admin_menu{
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

<!-- Dialog Form -->
<div id="dlg-admin_menu" class="easyui-dialog" style="width:400px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-admin_menu">
    <form id="fm-admin_menu" method="post" novalidate>
        <div class="fitem">
            <label for="type">Parent Menu</label>
            <input class="easyui-combobox" id="a_menu_parentId" name="a.a_menu_parentId" data-options="
                url : '<?php echo site_url('admin/menu/getParent'); ?>',
                method:'get', valueField:'a_menu_id', textField:'a_menu_name', panelHeight:'auto',
                onShowPanel: function(){
                    $('#a_menu_parentId').combobox('reload');
            }">
        </div>
        <div class="fitem">
            <label for="type">Nama Menu</label>
            <input type="text" id="a_menu_name" name="a.a_menu_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">URI</label>
            <input type="text" id="a_menu_uri" name="a.a_menu_uri" class="easyui-textbox" />
        </div>
        <div class="fitem">
            <label for="type">Icon</label>
            <input type="text" id="a_menu_iconCls" name="a.a_menu_iconCls" class="easyui-textbox" />
        </div>
        <div class="fitem">
            <label for="type">Type</label>
            <input class="easyui-combobox" id="a_menu_type" name="a.a_menu_type" data-options="
                url:'<?php echo site_url('admin/menu/enumType'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'auto'" />
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-admin_menu">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="adminMenuSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-admin_menu').dialog('close')">Batal</a>
</div>

<!-- End of file v_menu.php -->
<!-- Location: ./application/views/admin/v_menu.php -->