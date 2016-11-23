<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_operator"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_operator">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_operator_nik'"   width="200" align="center" sortable="true">NIK</th>
            <th data-options="field:'m_operator_name'"  width="400" halign="center" align="left" sortable="true">Nama Operator</th>
            <th data-options="field:'m_operator_auth'"  width="400" halign="center" align="center" sortable="true">Auth</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_operator = [{
        id      : 'master_operator-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterOperatorCreate();}
    },{
        id      : 'master_operator-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterOperatorUpdate();}
    },{
        id      : 'master_operator-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterOperatorHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterOperatorRefresh();}
    }];
    
    $('#grid-master_operator').datagrid({
        onLoadSuccess   : function(){
            $('#master_operator-edit').linkbutton('disable');
            $('#master_operator-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_operator-edit').linkbutton('enable');
            $('#master_operator-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_operator-edit').linkbutton('enable');
            $('#master_operator-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterOperatorUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/operator/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterOperatorRefresh() {
        $('#master_operator-edit').linkbutton('disable');
        $('#master_operator-delete').linkbutton('disable');
        $('#grid-master_operator').datagrid('reload');
    }
    
    function masterOperatorCreate() {
        $('#dlg-master_operator').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_operator').form('clear');
        url = '<?php echo site_url('master/operator/create'); ?>';
        $('#m_operator_nik').textbox('readonly', false);
        
    }
    
    function masterOperatorUpdate() {
        var row = $('#grid-master_operator').datagrid('getSelected');
        if(row){
            $('#dlg-master_operator').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_operator').form('load',row);
            url = '<?php echo site_url('master/operator/update'); ?>/' + row.m_operator_nik;
            $('#m_operator_nik').textbox('readonly', true);
        }
        else {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterOperatorSave(){
        $('#fm-master_operator').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_operator').dialog('close');
                    masterOperatorRefresh();
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
        
    function masterOperatorHapus(){
        var row = $('#grid-master_operator').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Operator '+row.m_operator_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/operator/delete'); ?>',{m_operator_nik:row.m_operator_nik},function(result){
                        if (result.success) {
                            masterOperatorRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else {
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
        else {
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
    #fm-master_operator{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_operator-upload{
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
<div id="dlg-master_operator" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_operator">
    <form id="fm-master_operator" method="post" novalidate>        
        <div class="fitem">
            <label for="type">NIK</label>
            <input type="text" id="m_operator_nik" name="m_operator_nik" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Operator</label>
            <input type="text" id="m_operator_name" name="m_operator_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Auth</label>
            <select id="m_operator_auth" name="m_operator_auth" class="easyui-combobox" data-options="panelHeight:'auto'" required="true">
                <option value="0">NO</option>
                <option value="1">YES</option>
            </select>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_operator">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterOperatorSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_operator').dialog('close');">Batal</a>
</div>

<!-- End of file v_operator.php -->
<!-- Location: ./application/views/master/v_operator.php -->