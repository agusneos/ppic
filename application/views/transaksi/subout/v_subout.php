<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
        parser:function(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    });
</script>
<!-- Data Grid -->
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false">
        <table id="grid-transaksi_subout"
            data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                        fit:true, fitColumns:true, toolbar:toolbar_transaksi_subout">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true" ></th>
                    <th data-options="field:'t_sub_in_head_no'"       width="100" halign="center" align="center" sortable="true" >Nomor SJ</th>
                    <th data-options="field:'m_vend_name'"     width="100" halign="center" align="center" sortable="true" >Nama Vendor</th>
                    <th data-options="field:'m_process_cat_name'"     width="100" halign="center" align="center" sortable="true" >Jenis Proses</th>
                    <th data-options="field:'t_sub_in_head_date'"     width="100" halign="center" align="center" sortable="true" >Tanggal</th>
                    <th data-options="field:'m_operator_name'"     width="100" halign="center" align="center" sortable="true" >Operator</th>
                    <th data-options="field:'t_sub_in_head_unit'"     width="100" halign="center" align="center" sortable="true" formatter="unit">Unit</th>
                    <th data-options="field:'t_sub_in_head_retur'"     width="100" halign="center" align="center" sortable="true" formatter="retur">Retur</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div data-options="region:'south',split:true,border:true" style="height:300px">
        <table id="grid-transaksi_subout_detail"
            data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                        fit:true, fitColumns:true, toolbar:toolbar_transaksi_subout_detail">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true" ></th>
                    <th data-options="field:'t_po_detail_item'"     width="100" halign="center" align="center" sortable="true">Kode Barang</th>
                    <th data-options="field:'m_item_name'"          width="250" halign="center" align="left"   sortable="true">Nama Barang</th>
                    <th data-options="field:'t_po_detail_no'"       width="80"  halign="center" align="center" sortable="true">No. PO</th>
                    <th data-options="field:'t_prod_lot'"           width="80"  halign="center" align="center" sortable="true">LOT</th>
                    <th data-options="field:'t_prod_sublot'"        width="80"  halign="center" align="center" sortable="true">SUBLOT</th>
                    <th data-options="field:'t_prod_id'"            width="80"  halign="center" align="center" sortable="true">Box</th>
                    <th data-options="field:'m_process_weight'"     width="80"  halign="center" align="center" sortable="true" formatter="berat">Berat Kg</th>
                    <th data-options="field:'t_sub_in_line_qty'"    width="80"  halign="center" align="center" sortable="true">Pcs</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    var toolbar_transaksi_subout = [{
        id      : 'transaksi_subout-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiSuboutCreate();}
    },{
        id      : 'transaksi_subout-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiSuboutUpdate();}
    },{
        id      : 'transaksi_subout-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiSuboutHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiSuboutRefresh();}
    }];
    
    $('#grid-transaksi_subout').datagrid({
        onLoadSuccess   : function(){
            $('#transaksi_subout-edit').linkbutton('disable');
            $('#transaksi_subout-delete').linkbutton('disable');
            disableDetail();
        },
        onSelect        : function(){
            $('#transaksi_subout-edit').linkbutton('enable');
            $('#transaksi_subout-delete').linkbutton('enable');
        },
        onClickRow      : function(index,row){
            $('#transaksi_subout-edit').linkbutton('enable');
            $('#transaksi_subout-delete').linkbutton('enable');
            var transaksiSuboutNilai = row.t_sub_in_head_id;
            var transaksiSuboutProc  = row.t_sub_in_head_proc;
            $('#grid-transaksi_subout_detail').datagrid('load','<?php echo site_url('transaksi/subout/detailIndex'); ?>?grid=true&nilai='+transaksiSuboutNilai+'-'+transaksiSuboutProc);
            enableDetail();
        },
        onDblClickRow   : function(){
            transaksiSuboutUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('transaksi/subout/headIndex'); ?>?grid=true'})
    .datagrid('enableFilter');

    function transaksiSuboutRefresh() {
        $('#transaksi_subout-edit').linkbutton('disable');
        $('#transaksi_subout-delete').linkbutton('disable');
        $('#grid-transaksi_subout').datagrid('reload');
        var transaksiSuboutNilai = null;
        var transaksiSuboutProc  = null;
        $('#grid-transaksi_subout_detail').datagrid('load','<?php echo site_url('transaksi/subout/detailIndex'); ?>?grid=true&nilai='+transaksiSuboutNilai+'-'+transaksiSuboutProc);
        $('#grid-transaksi_subout_detail').datagrid('reload');
        disableDetail();
    }
    
    function transaksiSuboutCreate() {
        $('#dlg-transaksi_subout').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_subout').form('clear');
        url = '<?php echo site_url('transaksi/subout/headCreate'); ?>';
        //$('#t_po_header_date').datebox('textbox').mask("99/99/9999",{placeholder:" "}); 
    }
    
    function transaksiSuboutUpdate() {
        var row = $('#grid-transaksi_subout').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_subout').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_subout').form('load',row);
            url = '<?php echo site_url('transaksi/subout/headUpdate'); ?>/' + row.t_sub_in_head_id;
        }
        else {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiSuboutSave(){
        $('#fm-transaksi_subout').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-transaksi_subout').dialog('close');
                    transaksiSuboutRefresh();
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
        
    function transaksiSuboutHapus(){
        var row = $('#grid-transaksi_subout').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus SJ \n'+row.t_sub_in_head_no+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/subout/headDelete'); ?>',{t_sub_in_head_id:row.t_sub_in_head_id},function(result){
                        if (result.success)
                        {
                            transaksiSuboutRefresh();
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
    
    function disableDetail()
    {
        $('#transaksi_subout_detail-new').linkbutton('disable');
        $('#transaksi_subout_detail-edit').linkbutton('disable');
        $('#transaksi_subout_detail-delete').linkbutton('disable');
        $('#transaksi_subout_detail-upload').linkbutton('disable');
        $('#transaksi_subout_detail-refresh').linkbutton('disable');
        $('#transaksi_subout_detail-generate_prod').linkbutton('disable');
        $('#transaksi_subout_detail-view_lot').linkbutton('disable');
    }
    
    function enableDetail()
    {
        $('#transaksi_subout_detail-new').linkbutton('enable');
        $('#transaksi_subout_detail-upload').linkbutton('enable');
        $('#transaksi_subout_detail-refresh').linkbutton('enable');
        $('#transaksi_subout_detail-generate_prod').linkbutton('disable');
        $('#transaksi_subout_detail-view_lot').linkbutton('disable');
    }
    
    function retur(value,row,index) {
        if(row.t_sub_in_head_retur == 1){
            return value='RETUR';
        }
        else {
            return value='';
        }
    }
    
    function unit(value,row,index) {
        if(row.t_sub_in_head_unit == 1){
            return value='Pcs';
        }
        else {
            return value='Kg';
        }
    }
    
    function berat(value,row,index) {
        return value=precisionRound(row.m_process_weight, 2);
    }
    
    function precisionRound(number, precision) {
        var factor = Math.pow(10, precision);
        return Math.round(number * factor) / factor;
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
    #fm-transaksi_subout{
        margin:0;
        padding:10px 30px;
    }
    #fm-transaksi_subout-upload{
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
<div id="dlg-transaksi_subout" class="easyui-dialog" style="width:400px; height:320px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_subout">
    <form id="fm-transaksi_subout" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nomor SJ</label>
            <input type="text" id="t_sub_in_head_no" name="t_sub_in_head_no" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Vendor</label>
            <input type="text" id="t_sub_in_head_vendor" name="t_sub_in_head_vendor" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/subout/getVendor'); ?>',
                method:'get', valueField:'m_vend_id', textField:'m_vend_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Jenis Proses</label>
            <input type="text" id="t_sub_in_head_proc" name="t_sub_in_head_proc" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/subout/getProc'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Tanggal SJ</label>
            <input type="text" id="t_sub_in_head_date" name="t_sub_in_head_date" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Operator</label>
            <input type="text" id="t_sub_in_head_opr" name="t_sub_in_head_opr" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/subout/getOpr'); ?>',
                method:'get', valueField:'m_operator_nik', textField:'m_operator_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Unit</label>
            <select id="t_sub_in_head_unit" name="t_sub_in_head_unit" class="easyui-combobox" data-options="panelHeight:'auto'" required="true">
                <option value="0">Kg</option>
                <option value="1">Pcs</option>
            </select>
        </div>
        <div class="fitem">
            <label for="type">Retur</label>
            <select id="t_sub_in_head_retur" name="t_sub_in_head_retur" class="easyui-combobox" data-options="panelHeight:'auto'" required="true">
                <option value="0">NO</option>
                <option value="1">YES</option>
            </select>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_subout">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiSuboutSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_subout').dialog('close');">Batal</a>
</div>


<!-- DETAIL -->
<script type="text/javascript">
    
    var toolbar_transaksi_subout_detail = [{
        id      : 'transaksi_subout_detail-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiSuboutDetailCreate();}
    },{
        id      : 'transaksi_subout_detail-refresh',
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiSuboutDetailRefresh();}
    }];
    
    $('#grid-transaksi_subout_detail').datagrid({
        onLoadSuccess   : function(){
            $('#transaksi_subout_detail-new').linkbutton('enable');
            $('#transaksi_subout_detail-upload').linkbutton('enable');
            $('#transaksi_subout_detail-refresh').linkbutton('enable');
            $('#transaksi_subout_detail-edit').linkbutton('disable');
            $('#transaksi_subout_detail-delete').linkbutton('disable');
            $('#transaksi_subout_detail-generate_prod').linkbutton('disable');
            $('#transaksi_subout_detail-view_lot').linkbutton('disable');
        },
        onSelect        : function(){
            $('#transaksi_subout_detail-edit').linkbutton('enable');
            $('#transaksi_subout_detail-delete').linkbutton('enable');
            $('#transaksi_subout_detail-generate_prod').linkbutton('enable');
            $('#transaksi_subout_detail-view_lot').linkbutton('enable');
        },
        onClickRow      : function(index,row){
            $('#transaksi_subout_detail-edit').linkbutton('enable');
            $('#transaksi_subout_detail-delete').linkbutton('enable');
            $('#transaksi_subout_detail-generate_prod').linkbutton('enable');
            $('#transaksi_subout_detail-view_lot').linkbutton('enable');
        },
        onDblClickRow   : function(){
            transaksiSuboutDetailUpdate();
        },
        view            :scrollview,
        remoteFilter    :true})
    .datagrid('enableFilter');
    
    
    
    function transaksiSuboutDetailRefresh() {
        $('#transaksi_subout_detail-edit').linkbutton('disable');
        $('#transaksi_subout_detail-delete').linkbutton('disable');
        $('#transaksi_subout_detail-generate_prod').linkbutton('disable');
        $('#transaksi_subout_detail-view_lot').linkbutton('disable');
        $('#grid-transaksi_subout_detail').datagrid('reload');
    }
    
    function transaksiSuboutDetailCreate() {
        var row = $('#grid-transaksi_subout').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_subout_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
            $('#fm-transaksi_subout_detail').form('clear');
            url = '<?php echo site_url('transaksi/subout/detailCreate'); ?>';
            $('#t_subout_detail_head').textbox('setValue',row.t_sub_in_head_id);
            $('#t_subout_detail_procid').textbox('setValue',row.m_process_cat_id);
            $('#t_subout_detail_proc').textbox('setValue',row.m_process_cat_name);
            $('#t_subout_detail_proc_tbl').textbox('setValue',row.m_process_cat_table);
            $('#t_subout_detail_nik').textbox('setValue',row.m_operator_nik);
            $('#t_subout_detail_kartu').next().find('input').focus();
        }
        else{
            $.messager.alert('Info','Header belum dipilih !','info');
        }
        
    }
    
    function transaksiSuboutDetailUpdate() {
        var row1 = $('#grid-transaksi_subout').datagrid('getSelected');
        if(row1){
            var row2 = $('#grid-transaksi_subout_detail').datagrid('getSelected');
            if(row2){
                $('#dlg-transaksi_subout_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
                $('#fm-transaksi_subout_detail').form('load',row2);
                url = '<?php echo site_url('transaksi/po/detailUpdate'); ?>/'+ row2.t_po_detail_lot_no;
                $('#t_po_detail_no').textbox('setValue',row1.t_po_header_no);
                $('#t_po_detail_date').datebox('setValue',row1.t_po_header_date);
                $('#t_po_detail_lot_no').textbox('disable');
            }
            else {
                 $.messager.alert('Info','Data belum dipilih !','info');
            }
        }
        else{
            $.messager.alert('Info','Header belum dipilih !','info');
        }
    }
    
    function transaksiSuboutDetailSave(){
        $('#fm-transaksi_subout_detail').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    //$('#dlg-transaksi_subout_detail').dialog('close');
                    transaksiSuboutDetailRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Disimpan</div>'
                    });
                    $('#t_subout_detail_kartu').textbox('setValue', '');
                    $('#t_subout_detail_kartu').next().find('input').focus();
                }
                else
                {
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Disimpan !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                    $('#t_subout_detail_kartu').textbox('setValue', '');
                    $('#t_subout_detail_kartu').next().find('input').focus();
                }
            }
        });
    }
    
    function transaksiSuboutDetailHapus(){
        var row = $('#grid-transaksi_subout_detail').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus LOT \n'+row.t_po_detail_lot_no+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/po/detailDelete'); ?>',{t_po_detail_lot_no:row.t_po_detail_lot_no},function(result){
                        if (result.success)
                        {
                            transaksiSuboutDetailRefresh();
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
    
    function transaksiSuboutDetailUpload()
    {
        $('#dlg-transaksi_subout_detail-upload').dialog({modal: true}).dialog('open').dialog('setTitle','Upload File');
        $('#fm-transaksi_subout_detail-upload').form('reset');
        urls = '<?php echo site_url('transaksi/po/detailUpload'); ?>/';
    }
    
    function transaksiSuboutDetailUploadSave()
    {
        $('#fm-transaksi_subout_detail-upload').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success)
                {
                    $('#dlg-transaksi_subout_detail-upload').dialog('close');
                    transaksiSuboutDetailRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : result.total + ' ' +result.ok + ' ' + result.ng
                    });
                } 
                else 
                {
                    $.messager.show({
                        title   : 'Error',
                        msg     : 'Upload Data Gagal'
                    });
                }
            }
        });
    }
    
    $('#detailPo').filebox({
        accept: ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    });
    
    $('#t_subout_detail_kartu').numberbox({
        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
            keypress: function(e){
                var result = $.fn.numberbox.defaults.inputEvents.keypress.call(this, e);
                if (e.keyCode == 13){
                    var scid = $('#t_subout_detail_kartu').textbox('getValue');
                    if(scid!=''){
                        transaksiSuboutDetailSave();
                    }
                }
                return result;
            }
        })
    });

    function calcProdQty(){
        var itemBox = $('#t_po_detail_item').combobox('getValue');
        var qtyPo   = $('#t_po_detail_qty').numberbox('getValue');
        $.post('<?php echo site_url('transaksi/po/calcProdQty'); ?>',{m_item_id:itemBox},function(result){
            if (result.success){
                var qty = Math.ceil(qtyPo/result.qty);
                $('#t_po_detail_prod').numberbox('setValue', qty*result.qty);
            }
        },'json');
    }
    
    function transaksiGenerateProd(){
        var row = $('#grid-transaksi_subout_detail').datagrid('getSelected');
        if (row){
            $.messager.progress({
                title   :'Please waiting',
                msg     :'Executing...'
            });
            $.post('<?php echo site_url('transaksi/po/detailGenerateProd'); ?>',{item_id:row.t_po_detail_item, prod_qty:row.t_po_detail_prod, lot:row.t_po_detail_lot_no},function(result){
            if (result.success)
            {
                transaksiSuboutDetailRefresh();
                $.messager.show({
                    title   : 'Info',
                    msg     : '<div class="messager-icon messager-info"></div><div>Produksi Berhasil Dibuat</div>'
                });
                $.messager.progress('close');

            }
            else
            {
                $.messager.show({
                    title   : 'Error',
                    msg     : '<div class="messager-icon messager-error"></div><div>Produksi Gagal Dibuat !</div>'+result.error
                });
                $.messager.progress('close');
            }
            },'json');
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }        
    }
</script>

<div id="dlg-transaksi_subout_detail-upload" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-transaksi_subout_detail-upload">
    <form id="fm-transaksi_subout_detail-upload" method="post" enctype="multipart/form-data" novalidate>       
        <div class="fitem">
            <label for="type">File</label>
            <input id="detailPo" name="detailPo" class="easyui-filebox" required="true"/>
        </div> 
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-transaksi_subout_detail-upload">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiSuboutDetailUploadSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_subout_detail-upload').dialog('close');">Batal</a>
</div>


<!-- ----------- -->
<div id="dlg-transaksi_subout_detail" class="easyui-dialog" style="width:500px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_subout_detail">
    <form id="fm-transaksi_subout_detail" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Head ID</label>
            <input type="text" id="t_subout_detail_head" name="t_subout_detail_head" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Proc ID</label>
            <input type="text" id="t_subout_detail_procid" name="t_subout_detail_procid" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Proc</label>
            <input type="text" id="t_subout_detail_proc" name="t_subout_detail_proc" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tabel</label>
            <input type="text" id="t_subout_detail_proc_tbl" name="t_subout_detail_proc_tbl" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nik</label>
            <input type="text" id="t_subout_detail_nik" name="t_subout_detail_nik" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Scan Kartu</label>
            <input type="text" id="t_subout_detail_kartu" name="t_subout_detail_kartu" class="easyui-numberbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_subout_detail">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiSuboutDetailSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_subout_detail').dialog('close');">Batal</a>
</div>

<!-- View LOT -->
<script type="text/javascript">
    //var cinta;
    function transaksiViewLot(){
        var row = $('#grid-transaksi_subout_detail').datagrid('getSelected');
        if (row)
        {
            $('#dlg-transaksi_subout_lot').dialog({
                title   : 'LOT - '+row.t_po_detail_lot_no,
                width   : 400,
                height  : 400,
                modal   : true
            });
            $('#dlg-transaksi_subout_lot').dialog('refresh', $('#grid-transaksi_subout_lot').datagrid('load', '<?php echo site_url('transaksi/po/lot'); ?>?grid=true&nilailot='+row.t_po_detail_lot_no));
        }
    }
    
    var toolbar_transaksi_subout_lot = [{
        id      : 'transaksi_subout_lot-print_all',
        text    : 'Print All',
        iconCls : 'icon-print',
        handler : function(){transaksiSuboutLotPrintAll();}
    },{
        id      : 'transaksi_subout_lot-print_sublot',
        text    : 'Print SubLot',
        iconCls : 'icon-print',
        handler : function(){transaksiSuboutLotPrintSublot();}
    },{
        id      : 'transaksi_subout_lot-print_selected',
        text    : 'Print Selected',
        iconCls : 'icon-print',
        handler : function(){transaksiSuboutLotPrintSelected();}
    }];

    $('#grid-transaksi_subout_lot').datagrid({        
        view            :scrollview,
        remoteFilter    :true})
    .datagrid('enableFilter');

    function transaksiSuboutLotPrintAll() {
        var row = $('#grid-transaksi_subout_detail').datagrid('getSelected');
        if (row)
        {
            var url = '<?php echo site_url('transaksi/po/printAll'); ?>/' + row.t_po_detail_lot_no;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-transaksi_subout_card_print').dialog({
                title   : 'LOT : '+row.t_po_detail_lot_no,
                content : content,
                modal   : true,
                iconCls : 'icon-print',
                plain   : true,
                width   : '80%',
                height  : '80%'
            });
        }    
    }
    
    function transaksiSuboutLotPrintSublot() {
        var row = $('#grid-transaksi_subout_lot').datagrid('getSelected');
        if (row)
        {
            var url = '<?php echo site_url('transaksi/po/printSublot'); ?>?lot='+row.t_prod_lot+'&sublot='+ row.t_prod_sublot;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-transaksi_subout_card_print').dialog({
                title   : 'LOT : '+row.t_prod_lot+' / SubLot : '+row.t_prod_sublot,
                content : content,
                modal   : true,
                iconCls : 'icon-print',
                plain   : true,
                width   : '80%',
                height  : '80%'
            });
        }
        else
        {
            $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiSuboutLotPrintSelected() {
        var row = $('#grid-transaksi_subout_lot').datagrid('getSelected');
        if (row)
        {
            var url = '<?php echo site_url('transaksi/po/printSelected'); ?>/' + row.t_prod_id;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-transaksi_subout_card_print').dialog({
                title   : 'LOT : '+row.t_prod_lot+' / SubLot : '+row.t_prod_sublot+' / Card : '+row.t_prod_card,
                content : content,
                modal   : true,
                iconCls : 'icon-print',
                plain   : true,
                width   : '80%',
                height  : '80%'
            });
        }
        else
        {
            $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
</script>

<div id="dlg-transaksi_subout_lot">
    <table id="grid-transaksi_subout_lot"
        data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                    fit:true, fitColumns:true, toolbar:toolbar_transaksi_subout_lot">
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true" ></th>
                <th data-options="field:'t_prod_id'"        width="100"  align="center" halign="center" sortable="false">Id</th>
                <th data-options="field:'t_prod_lot'"       width="100"  align="center" halign="center" sortable="false">LOT</th>
                <th data-options="field:'t_prod_sublot'"    width="100"  align="center" halign="center" sortable="false">Sub LOT</th>
                <th data-options="field:'t_prod_card'"      width="100"  align="center" halign="center" sortable="false">Kartu</th>
                <th data-options="field:'t_prod_qty'"       width="100"  align="right"  halign="center" sortable="false">Quantity</th>
            </tr>
        </thead>    
    </table>
</div>

<div id="dlg-transaksi_subout_card_print">
    
</div>
<!-- End of file v_po.php -->
<!-- Location: ./application/views/transaksi/v_subout.php -->