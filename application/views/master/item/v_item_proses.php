<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_item-proses"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_item_proses">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_process_seq'"        width="100"  align="center" halign="center" sortable="false">No. Proses</th>
            <th data-options="field:'m_process_cat_name'"       width="100"  align="center" halign="center" sortable="false">Nama Proses</th>
            <th data-options="field:'m_process_loc'"        width="100"  align="center" halign="center" sortable="false" formatter="loc">Lokasi</th>
            <th data-options="field:'m_process_weight'"     width="100"  align="right"  halign="center" sortable="false">Berat Proses Gr</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_master_item_proses = [{
        id      : 'master_item-proses-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterItemProsesCreate();}
    },{
        id      : 'master_item-proses-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterItemProsesUpdate();}
    },{
        id      : 'master_item-proses-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterItemProsesHapus();}
    },{
        id      : 'master_item-proses-refresh',
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterItemProsesRefresh();}
    },{
        id      : 'master_item-proses-copy',
        text    : 'Copy From',
        iconCls : 'icon-application_double',
        handler : function(){masterItemProsesCopy();}
    }];
    
    $('#grid-master_item-proses').datagrid({        
        onLoadSuccess   : function(){
            masterItemCheckValidateProsesLoad();
        },
        onSelect        : function(){
            masterItemCheckValidateProsesSelect();
        },
        onClickRow      : function(){
            masterItemCheckValidateProsesSelect();
        },
        onDblClickRow   : function(){
            masterItemCheckValidateProsesDblClick();
        },
        url             :'<?php echo site_url('master/item/proses'); ?>?grid=true&item='+item});
 
    function loc(value,row,index) {
        if(row.m_process_loc == 1){
            return value='EKSTERNAL';
        }
        else {
            return value='';
        }
    }
    
    function masterItemCheckValidateProsesLoad() {
        var row = $('#grid-master_item').datagrid('getSelected');
        if(row){
            if(row.m_item_route_stat==1){
                $('#master_item-proses-new').linkbutton('disable');
                $('#master_item-proses-edit').linkbutton('disable');
                $('#master_item-proses-delete').linkbutton('disable');
                $('#master_item-proses-copy').linkbutton('disable');
            }
            else{
                $('#master_item-proses-new').linkbutton('enable');
                $('#master_item-proses-edit').linkbutton('disable');
                $('#master_item-proses-delete').linkbutton('disable');
                $('#master_item-proses-copy').linkbutton('enable');
            }
        }
    }

    function masterItemCheckValidateProsesSelect() {
        var row = $('#grid-master_item').datagrid('getSelected');
        if(row){
            if(row.m_item_route_stat==1){
                $('#master_item-proses-new').linkbutton('disable');
                $('#master_item-proses-edit').linkbutton('disable');
                $('#master_item-proses-delete').linkbutton('disable');
                $('#master_item-proses-copy').linkbutton('disable');
            }
            else{
                $('#master_item-proses-new').linkbutton('enable');
                $('#master_item-proses-edit').linkbutton('enable');
                $('#master_item-proses-delete').linkbutton('enable');
                $('#master_item-proses-copy').linkbutton('enable');
            }
        }
    }

    function masterItemCheckValidateProsesDblClick() {
        var row = $('#grid-master_item').datagrid('getSelected');
        if(row){
            if(row.m_item_route_stat==0){
                masterItemProsesUpdate();
            }
        }
    }
        
    function masterItemProsesRefresh(){
        $('#master_item-proses-edit').linkbutton('disable');
        $('#master_item-proses-delete').linkbutton('disable');
        $('#grid-master_item-proses').datagrid('reload');
    }
    
    function masterItemProsesCreate(){
        $('#dlg-master_item-proses_entry').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_item-proses_entry').form('clear');
        url = '<?php echo site_url('master/item/prosesCreate'); ?>/' + item;
        $('#m_process_seq').numberbox('enable');
        $.post('<?php echo site_url('master/item/getProcSeq'); ?>',{m_process_id:item},function(result){
            $('#m_process_seq').numberbox('setValue', eval(result.seq)+1);
        },'json');
    }
    
    function masterItemProsesUpdate(){
        var row = $('#grid-master_item-proses').datagrid('getSelected');
        if(row){
            $('#dlg-master_item-proses_entry').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_item-proses_entry').form('load',row);
            url = '<?php echo site_url('master/item/prosesUpdate'); ?>/' + row.m_process_id+'-'+row.m_process_seq;
            $('#m_process_seq').numberbox('disable');           
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterItemProsesSave(){
        $('#fm-master_item-proses_entry').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_item-proses_entry').dialog('close');
                    masterItemProsesRefresh();
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
    
    function masterItemProsesHapus(){
        var row = $('#grid-master_item-proses').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Proses \n'+row.m_process_seq+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/item/prosesDelete'); ?>',{m_process_id:row.m_process_id, m_process_seq:row.m_process_seq},function(result){
                        if (result.success){
                            masterItemProsesRefresh();
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
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterItemProsesCopy(){
        $('#dlg-master_item-proses_copy').dialog({modal: true}).dialog('open').dialog('setTitle','Copy From');
        $('#fm-master_item-proses_copy').form('clear');
    }
    
    function masterItemProsesCopySave(){
        $('#fm-master_item-proses_copy').form('submit',{
            url: '<?php echo site_url('master/item/prosesCopy'); ?>/' + item,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_item-proses_copy').dialog('close');
                    masterItemProsesRefresh();
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


<!-- PROSES -->
<div id="dlg-master_item-proses_entry" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_item-proses_entry">
    <form id="fm-master_item-proses_entry" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nomor Proses</label>
            <input type="text" id="m_process_seq" name="m_process_seq" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Proses</label>
            <input type="text" id="m_process_cat_name" name="m_process_cat_name" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/item/getProses'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', 
                onSelect: function(rec){
                    var url = rec.m_process_cat_name;
                    if(url.indexOf('Plating') >= 0){
                        $('#m_process_loc').combobox('setValue', 1);
                    }
                    else {
                        $('#m_process_loc').combobox('setValue', 0);
                    }                    
                }, panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Lokasi</label>
            <select id="m_process_loc" name="m_process_loc" class="easyui-combobox" data-options="panelHeight:'auto'" required="true">
                <option value="0">INTERNAL</option>
                <option value="1">EKSTERNAL</option>
            </select>
        </div>
        <div class="fitem">
            <label for="type">Berat Proses Gr</label>
            <input type="text" id="m_process_weight" name="m_process_weight" class="easyui-numberbox" precision="2" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_item-proses_entry">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterItemProsesSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_item-proses_entry').dialog('close');">Batal</a>
</div>

<!-- Dialog Copy Proses -->
<div id="dlg-master_item-proses_copy" class="easyui-dialog" style="width:500px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_item-proses_copy">
    <form id="fm-master_item-proses_copy" method="post" novalidate>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="copy_item_proses" name="copy_item_proses" style="width:250px;" class="easyui-combobox" required="true"
                data-options="
                method:'get', valueField:'m_process_id', textField:'m_item_name', 
                onShowPanel: function(){
                    var url = '<?php echo site_url('master/item/getItemProses'); ?>';
                    $('#copy_item_proses').combobox('reload', url);
                },panelHeight:'150'"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_item-proses_copy">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterItemProsesCopySave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_item-proses_copy').dialog('close');">Batal</a>
</div>

<!-- End of file v_item_proses.php -->
<!-- Location: ./application/views/master/v_item_proses.php -->