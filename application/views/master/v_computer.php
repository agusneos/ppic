<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_computer"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_computer">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_computer_id'"    width="200" align="center" sortable="true">Kode Komputer</th>
            <th data-options="field:'m_computer_name'"  width="400" halign="center" align="left" sortable="true">Nama Komputer</th>
            <th data-options="field:'m_computer_ip'"  width="400" halign="center" align="left" sortable="true">IP Komputer</th>
            <th data-options="field:'m_process_cat_name'"  width="400" halign="center" align="left" sortable="true">Nama Proses</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_computer = [{
        id      : 'master_computer-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterComputerCreate();}
    },{
        id      : 'master_computer-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterComputerUpdate();}
    },{
        id      : 'master_computer-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterComputerHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterComputerRefresh();}
    }];
    
    $('#grid-master_computer').datagrid({
        onLoadSuccess   : function(){
            $('#master_computer-edit').linkbutton('disable');
            $('#master_computer-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_computer-edit').linkbutton('enable');
            $('#master_computer-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_computer-edit').linkbutton('enable');
            $('#master_computer-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterComputerUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/computer/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterComputerRefresh() {
        $('#master_computer-edit').linkbutton('disable');
        $('#master_computer-delete').linkbutton('disable');
        $('#grid-master_computer').datagrid('reload');
    }
    
    function masterComputerCreate() {
        $('#dlg-master_computer').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_computer').form('clear');
        url = '<?php echo site_url('master/computer/create'); ?>';
    }
    
    function masterComputerUpdate() {
        var row = $('#grid-master_computer').datagrid('getSelected');
        if(row){
            $('#dlg-master_computer').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_computer').form('load',row);
            url = '<?php echo site_url('master/computer/update'); ?>/' + row.m_computer_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterComputerSave(){
        $('#fm-master_computer').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_computer').dialog('close');
                    masterComputerRefresh();
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
        
    function masterComputerHapus(){
        var row = $('#grid-master_computer').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Computer '+row.m_computer_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/computer/delete'); ?>',{m_computer_id:row.m_computer_id},function(result){
                        if (result.success)
                        {
                            masterComputerRefresh();
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
    #fm-master_computer{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_computer-upload{
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
<div id="dlg-master_computer" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_computer">
    <form id="fm-master_computer" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Komputer</label>
            <input type="text" id="m_computer_name" name="m_computer_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">IP Komputer</label>
            <input type="text" id="m_computer_ip" name="m_computer_ip" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Proses</label>
            <input type="text" id="m_process_cat_id" name="m_process_cat_id" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/computer/getProces'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_computer">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterComputerSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_computer').dialog('close')">Batal</a>
</div>

<!-- End of file v_computer.php -->
<!-- Location: ./application/views/master/v_computer.php -->