<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_machine"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_machine">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_machine_id'"    width="200" align="center" sortable="true">Kode Mesin</th>
            <th data-options="field:'m_machine_name'"  width="400" halign="center" align="left" sortable="true">Nama Mesin</th>
            <th data-options="field:'m_machine_lines'"  width="400" halign="center" align="center" sortable="true">Line Mesin</th>
            <th data-options="field:'m_machine_mac'"  width="400" halign="center" align="center" sortable="true">Nomor Mesin</th>
            <th data-options="field:'m_process_cat_name'"  width="400" halign="center" align="left" sortable="true">Nama Proses</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_machine = [{
        id      : 'master_machine-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterMachineCreate();}
    },{
        id      : 'master_machine-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterMachineUpdate();}
    },{
        id      : 'master_machine-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterMachineHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterMachineRefresh();}
    }];
    
    $('#grid-master_machine').datagrid({
        onLoadSuccess   : function(){
            $('#master_machine-edit').linkbutton('disable');
            $('#master_machine-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_machine-edit').linkbutton('enable');
            $('#master_machine-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_machine-edit').linkbutton('enable');
            $('#master_machine-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterMachineUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/machine/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterMachineRefresh() {
        $('#master_machine-edit').linkbutton('disable');
        $('#master_machine-delete').linkbutton('disable');
        $('#grid-master_machine').datagrid('reload');
    }
    
    function masterMachineCreate() {
        $('#dlg-master_machine').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_machine').form('clear');
        url = '<?php echo site_url('master/machine/create'); ?>';
    }
    
    function masterMachineUpdate() {
        var row = $('#grid-master_machine').datagrid('getSelected');
        if(row){
            $('#dlg-master_machine').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_machine').form('load',row);
            url = '<?php echo site_url('master/machine/update'); ?>/' + row.m_machine_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterMachineSave(){
        $('#fm-master_machine').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_machine').dialog('close');
                    masterMachineRefresh();
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
        
    function masterMachineHapus(){
        var row = $('#grid-master_machine').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Machine '+row.m_machine_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/machine/delete'); ?>',{m_machine_id:row.m_machine_id},function(result){
                        if (result.success)
                        {
                            masterMachineRefresh();
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
    #fm-master_machine{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_machine-upload{
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
<div id="dlg-master_machine" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_machine">
    <form id="fm-master_machine" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Mesin</label>
            <input type="text" id="m_machine_name" name="m_machine_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">LINE Mesin</label>
            <input type="text" id="m_machine_lines" name="m_machine_lines" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">NOMOR Mesin</label>
            <input type="text" id="m_machine_mac" name="m_machine_mac" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Proses</label>
            <input type="text" id="m_process_cat_id" name="m_process_cat_id" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/machine/getProces'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_machine">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterMachineSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_machine').dialog('close')">Batal</a>
</div>

<!-- End of file v_machine.php -->
<!-- Location: ./application/views/master/v_machine.php -->