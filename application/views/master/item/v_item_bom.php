<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_item-bom"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_item_bom">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_item_bom_cat'"   width="100"  align="center" halign="center" sortable="false">Jenis</th>
            <th data-options="field:'m_item_bom_id'"    width="100"  align="center" halign="center" sortable="false">Kode Barang</th>
            <th data-options="field:'m_item_bom_name'"  width="100"  align="center" halign="center" sortable="false">Nama Barang</th>
            <th data-options="field:'m_bom_qty'"        width="100"  align="right"  halign="center" sortable="false">Quantity</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_master_item_bom = [{
        id      : 'master_item-bom-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterItemBomCreate();}
    },{
        id      : 'master_item-bom-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterItemBomUpdate();}
    },{
        id      : 'master_item-bom-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterItemBomHapus();}
    },{
        id      : 'master_item-bom-refresh',
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterItemBomRefresh();}
    },{
        id      : 'master_item-bom-copy',
        text    : 'Copy From',
        iconCls : 'icon-application_double',
        handler : function(){masterItemBomCopy();}
    }];
    
    $('#grid-master_item-bom').datagrid({        
        onLoadSuccess   : function(){
            masterItemCheckValidateBomLoad();
        },
        onSelect        : function(){
            masterItemCheckValidateBomSelect();
        },
        onClickRow      : function(){
            masterItemCheckValidateBomSelect();
        },
        onDblClickRow   : function(){
            masterItemCheckValidateBomDblClick();
        },
        url             :'<?php echo site_url('master/item/bom'); ?>?grid=true&item='+item});
        
    function masterItemCheckValidateBomLoad() {
        var row = $('#grid-master_item').datagrid('getSelected');
        if(row){
            if(row.m_item_bom_stat==1){
                $('#master_item-bom-new').linkbutton('disable');
                $('#master_item-bom-edit').linkbutton('disable');
                $('#master_item-bom-delete').linkbutton('disable');
                $('#master_item-bom-copy').linkbutton('disable');
            }
            else{
                $('#master_item-bom-new').linkbutton('enable');
                $('#master_item-bom-edit').linkbutton('disable');
                $('#master_item-bom-delete').linkbutton('disable');
                $('#master_item-bom-copy').linkbutton('enable');
            }
        }
    }

    function masterItemCheckValidateBomSelect() {
        var row = $('#grid-master_item').datagrid('getSelected');
        if(row){
            if(row.m_item_bom_stat==1){
                $('#master_item-bom-new').linkbutton('disable');
                $('#master_item-bom-edit').linkbutton('disable');
                $('#master_item-bom-delete').linkbutton('disable');
                $('#master_item-bom-copy').linkbutton('disable');
            }
            else{
                $('#master_item-bom-new').linkbutton('enable');
                $('#master_item-bom-edit').linkbutton('enable');
                $('#master_item-bom-delete').linkbutton('enable');
                $('#master_item-bom-copy').linkbutton('enable');
            }
        }
    }

    function masterItemCheckValidateBomDblClick() {
        var row = $('#grid-master_item').datagrid('getSelected');
        if(row){
            if(row.m_item_bom_stat==0){
                masterItemBomUpdate();
            }
        }
    }
    
    function masterItemBomRefresh(){
        $('#master_item-bom-edit').linkbutton('disable');
        $('#master_item-bom-delete').linkbutton('disable');
        $('#grid-master_item-bom').datagrid('reload');
    }
    
    function masterItemBomCreate(){
        $('#dlg-master_item-bom_entry').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_item-bom_entry').form('clear');
        url = '<?php echo site_url('master/item/bomCreate'); ?>/' + item;
        $('#m_item_bom_name').combobox('enable');
        $('#m_item_bom_cat').combobox('enable');
    }
    
    function masterItemBomUpdate(){
        var row = $('#grid-master_item-bom').datagrid('getSelected');
        if(row){
            $('#dlg-master_item-bom_entry').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_item-bom_entry').form('load',row);
            url = '<?php echo site_url('master/item/bomUpdate'); ?>/' + row.m_bom_id+'-'+row.m_bom_item;
            $('#m_item_bom_cat').combobox('disable');
            $('#m_item_bom_name').combobox('disable');
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterItemBomSave(){
        $('#fm-master_item-bom_entry').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_item-bom_entry').dialog('close');
                    masterItemBomRefresh();
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
    
    function masterItemBomHapus(){
        var row = $('#grid-master_item-bom').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Bom \n'+row.m_item_bom_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/item/bomDelete'); ?>',{m_bom_id:row.m_bom_id, m_bom_item:row.m_bom_item},function(result){
                        if (result.success){
                            masterItemBomRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else{
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
    
    function masterItemBomCopy(){
        $('#dlg-master_item-bom_copy').dialog({modal: true}).dialog('open').dialog('setTitle','Copy From');
        $('#fm-master_item-bom_copy').form('clear');
    }
    
    function masterItemBomCopySave(){
        $('#fm-master_item-bom_copy').form('submit',{
            url: '<?php echo site_url('master/item/bomCopy'); ?>/' + item,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_item-bom_copy').dialog('close');
                    masterItemBomRefresh();
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
    
</script>

<!-- BOM -->
<div id="dlg-master_item-bom_entry" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_item-bom_entry">
    <form id="fm-master_item-bom_entry" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Jenis BOM</label>
            <input type="text" id="m_item_bom_cat" name="m_item_bom_cat" style="width:150px;" class="easyui-combobox" required="true"
                   data-options="url:'<?php echo site_url('master/item/enumBomCat'); ?>',
                   method:'get', valueField:'data', textField:'data', 
                   onSelect: function(rec){
                        var url = '<?php echo site_url('master/item/getBomItem'); ?>/'+rec.data;
                        $('#m_item_bom_name').combobox('reload', url);
                        $('#m_bom_qty').numberbox('setValue', '');
                    }, panelHeight:'auto'" />
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="m_item_bom_name" name="m_item_bom_name" style="width:150px;" class="easyui-combobox" required="true"
                   data-options="valueField:'m_item_bom_id', textField:'m_item_bom_name', 
                   onSelect: function(rec){
                        $.post('<?php echo site_url('master/item/getBomQty'); ?>',{m_item_bom_id:rec.m_item_bom_id},function(result){
                            if (result.success){
                                $('#m_bom_qty').numberbox('setValue', result.qty);
                            } else {
                                $('#m_bom_qty').numberbox('setValue', '');
                            }
                        },'json');
                        }, panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Quantity</label>
            <input type="text" id="m_bom_qty" name="m_bom_qty" class="easyui-numberbox" precision="2" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_item-bom_entry">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterItemBomSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_item-bom_entry').dialog('close');">Batal</a>
</div>

<!-- Dialog Copy Bom -->
<div id="dlg-master_item-bom_copy" class="easyui-dialog" style="width:500px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_item-bom_copy">
    <form id="fm-master_item-bom_copy" method="post" novalidate>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="copy_item_bom" name="copy_item_bom" style="width:250px;" class="easyui-combobox" required="true"
                data-options="
                method:'get', valueField:'m_bom_id', textField:'m_item_name', 
                onShowPanel: function(){
                    var url = '<?php echo site_url('master/item/getItemBom'); ?>';
                    $('#copy_item_bom').combobox('reload', url);
                },panelHeight:'150'"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_item-bom_copy">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterItemBomCopySave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_item-bom_copy').dialog('close');">Batal</a>
</div>
<!-- End of file v_item_bom.php -->
<!-- Location: ./application/views/master/v_item_bom.php -->