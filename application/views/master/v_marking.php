<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_marking"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:false, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_marking">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_marking_id'"    width="200" align="center" sortable="true">Kode Marking</th>
            <th data-options="field:'m_marking_name'"  width="400" halign="center" align="center" sortable="true">Nama Marking</th>
            <th data-options="field:'m_marking_img'"  width="400" halign="center" align="center" sortable="true">Path Marking</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_marking = [{
        id      : 'master_marking-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterMarkingCreate();}
    },{
        id      : 'master_marking-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterMarkingUpdate();}
    },{
        id      : 'master_marking-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterMarkingHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterMarkingRefresh();}
    }];
    
    $('#grid-master_marking').datagrid({
        onLoadSuccess   : function(){
            $('#master_marking-edit').linkbutton('disable');
            $('#master_marking-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_marking-edit').linkbutton('enable');
            $('#master_marking-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_marking-edit').linkbutton('enable');
            $('#master_marking-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterMarkingUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/marking/index'); ?>?grid=true'})
    .datagrid('enableFilter');
    
    function masterMarkingRefresh() {
        $('#master_marking-edit').linkbutton('disable');
        $('#master_marking-delete').linkbutton('disable');
        $('#grid-master_marking').datagrid('reload');
    }
    
    function masterMarkingCreate() {
        $('#dlg-master_marking').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_marking').form('clear');
        url = '<?php echo site_url('master/marking/create'); ?>';
        $('#m_marking_path').filebox('clear');
        $('#m_marking_path').filebox({required:true});
    }
    
    function masterMarkingUpdate() {
        var row = $('#grid-master_marking').datagrid('getSelected');
        if(row){
            $('#dlg-master_marking').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_marking').form('load',row);
            url = '<?php echo site_url('master/marking/update'); ?>/' + row.m_marking_id;
            $('#m_marking_path').filebox('clear');
            $('#m_marking_path').filebox({required:false});
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterMarkingSave(){
        $('#fm-master_marking').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_marking').dialog('close');
                    masterMarkingRefresh();
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
        
    function masterMarkingHapus(){
        var row = $('#grid-master_marking').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Marking '+row.m_marking_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/marking/delete'); ?>',{m_marking_id:row.m_marking_id},function(result){
                        if (result.success)
                        {
                            masterMarkingRefresh();
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
    
    $('#m_marking_path').filebox({
        accept: 'image/jpeg'
    });
        
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
    #fm-master_marking{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_marking-upload{
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
<div id="dlg-master_marking" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_marking">
    <form id="fm-master_marking" method="post" enctype="multipart/form-data" novalidate>        
        <div class="fitem">
            <label for="type">Nama Marking</label>
            <input type="text" id="m_marking_name" name="m_marking_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">File Marking</label>
            <input type="text" id="m_marking_path" name="m_marking_path" class="easyui-filebox" />
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_marking">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterMarkingSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_marking').dialog('close')">Batal</a>
</div>

<!-- End of file v_marking.php -->
<!-- Location: ./application/views/master/v_marking.php -->