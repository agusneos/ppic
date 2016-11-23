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
        <table id="grid-transaksi_po"
            data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                        fit:true, fitColumns:true, toolbar:toolbar_transaksi_po">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true" ></th>
                    <th data-options="field:'t_po_header_no'"       width="100" halign="center" align="center" sortable="true" >Nomor PO</th>
                    <th data-options="field:'t_po_header_cust'"     width="100" halign="center" align="center" sortable="true" >No. PO Cust</th>
                    <th data-options="field:'t_po_header_date'"     width="100" halign="center" align="center" sortable="true" >Tanggal PO</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div data-options="region:'south',split:true,border:true" style="height:300px">
        <table id="grid-transaksi_po_detail"
            data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                        fit:true, fitColumns:true, toolbar:toolbar_transaksi_po_detail">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true" ></th>
                    <th data-options="field:'t_po_detail_item'"         width="100" halign="center" align="center" sortable="true">Kode Barang</th>
                    <th data-options="field:'m_item_name'"              width="250" halign="center" align="left"   sortable="true">Nama Barang</th>
                    <th data-options="field:'t_po_detail_cust'"         width="80"  halign="center" align="center" sortable="true">Kode Cust.</th>
                    <th data-options="field:'m_cust_name'"              width="250" halign="center" align="left"   sortable="true">Nama Cust.</th>
                    <th data-options="field:'t_po_detail_qty'"          width="80"  halign="center" align="right"  sortable="true">Qty PO</th>
                    <th data-options="field:'t_po_detail_prod'"         width="80"  halign="center" align="right"  sortable="true">Qty Prod.</th>
                    <th data-options="field:'t_po_detail_lot_no'"       width="80"  halign="center" align="center" sortable="true">LOT</th>
                    <th data-options="field:'t_po_detail_prod_date'"    width="100" halign="center" align="center" sortable="true">Tgl. Prod.</th>
                    <th data-options="field:'t_po_detail_delv_date'"    width="100" halign="center" align="center" sortable="true">Tgl. Delv.</th>
                    <th data-options="field:'t_po_detail_prod_weight'"  width="80"  halign="center" align="right"  sortable="true">Berat Produksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    var toolbar_transaksi_po = [{
        id      : 'transaksi_po-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiPoCreate();}
    },{
        id      : 'transaksi_po-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiPoUpdate();}
    },{
        id      : 'transaksi_po-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiPoHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiPoRefresh();}
    }];
    
    $('#grid-transaksi_po').datagrid({
        onLoadSuccess   : function(){
            $('#transaksi_po-edit').linkbutton('disable');
            $('#transaksi_po-delete').linkbutton('disable');
            disableDetail();
        },
        onSelect        : function(){
            $('#transaksi_po-edit').linkbutton('enable');
            $('#transaksi_po-delete').linkbutton('enable');
        },
        onClickRow      : function(index,row){
            $('#transaksi_po-edit').linkbutton('enable');
            $('#transaksi_po-delete').linkbutton('enable');
            var transaksiPoNilai = row.t_po_header_no;
            $('#grid-transaksi_po_detail').datagrid('load','<?php echo site_url('transaksi/po/detailIndex'); ?>?grid=true&nilai='+transaksiPoNilai);
            enableDetail();
        },
        onDblClickRow   : function(){
            transaksiPoUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('transaksi/po/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function transaksiPoRefresh() {
        $('#transaksi_po-edit').linkbutton('disable');
        $('#transaksi_po-delete').linkbutton('disable');
        $('#grid-transaksi_po').datagrid('reload');
        var transaksiPoNilai = null;
        $('#grid-transaksi_po_detail').datagrid('load','<?php echo site_url('transaksi/po/detailIndex'); ?>?grid=true&nilai='+transaksiPoNilai);
        $('#grid-transaksi_po_detail').datagrid('reload');
        disableDetail();
    }
    
    function transaksiPoCreate() {
        $('#dlg-transaksi_po').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_po').form('clear');
        url = '<?php echo site_url('transaksi/po/create'); ?>';
        //$('#t_po_header_date').datebox('textbox').mask("99/99/9999",{placeholder:" "}); 
    }
    
    function transaksiPoUpdate() {
        var row = $('#grid-transaksi_po').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_po').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_po').form('load',row);
            url = '<?php echo site_url('transaksi/po/update'); ?>/';
            $('#t_po_header_no_old').textbox('setValue', row.t_po_header_no);
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiPoSave(){
        $('#fm-transaksi_po').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-transaksi_po').dialog('close');
                    transaksiPoRefresh();
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
        
    function transaksiPoHapus(){
        var row = $('#grid-transaksi_po').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus PO \n'+row.t_po_header_no+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/po/delete'); ?>',{t_po_header_no:row.t_po_header_no},function(result){
                        if (result.success)
                        {
                            transaksiPoRefresh();
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
        $('#transaksi_po_detail-new').linkbutton('disable');
        $('#transaksi_po_detail-edit').linkbutton('disable');
        $('#transaksi_po_detail-delete').linkbutton('disable');
        $('#transaksi_po_detail-upload').linkbutton('disable');
        $('#transaksi_po_detail-refresh').linkbutton('disable');
        $('#transaksi_po_detail-generate_prod').linkbutton('disable');
        $('#transaksi_po_detail-view_lot').linkbutton('disable');
    }
    
    function enableDetail()
    {
        $('#transaksi_po_detail-new').linkbutton('enable');
        $('#transaksi_po_detail-upload').linkbutton('enable');
        $('#transaksi_po_detail-refresh').linkbutton('enable');
        $('#transaksi_po_detail-generate_prod').linkbutton('disable');
        $('#transaksi_po_detail-view_lot').linkbutton('disable');
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
    #fm-transaksi_po{
        margin:0;
        padding:10px 30px;
    }
    #fm-transaksi_po-upload{
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
<div id="dlg-transaksi_po" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_po">
    <form id="fm-transaksi_po" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nomor PO Lama</label>
            <input type="text" id="t_po_header_no_old" name="t_po_header_no_old" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nomor PO</label>
            <input type="text" id="t_po_header_no" name="t_po_header_no" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">No. PO Cust</label>
            <input type="text" id="t_po_header_cust" name="t_po_header_cust" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tanggal PO</label>
            <input type="text" id="t_po_header_date" name="t_po_header_date" class="easyui-datebox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_po">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiPoSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_po').dialog('close');">Batal</a>
</div>


<!-- DETAIL -->
<script type="text/javascript">
    var toolbar_transaksi_po_detail = [{
        id      : 'transaksi_po_detail-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiPoDetailCreate();}
    },{
        id      : 'transaksi_po_detail-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiPoDetailUpdate();}
    },{
        id      : 'transaksi_po_detail-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiPoDetailHapus();}
    },{
        id      : 'transaksi_po_detail-upload',
        text    : 'Upload',
        iconCls : 'icon-upload',
        handler : function(){transaksiPoDetailUpload();}
    },{
        id      : 'transaksi_po_detail-refresh',
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiPoDetailRefresh();}
    },{
        id      : 'transaksi_po_detail-generate_prod',
        text    : 'Generate Prod.',
        iconCls : 'icon-hammer_plus',
        handler : function(){transaksiGenerateProd();}
    },{
        id      : 'transaksi_po_detail-view_lot',
        text    : 'View LOT.',
        iconCls : 'icon-hammer_plus',
        handler : function(){transaksiViewLot();}
    }];
    
    $('#grid-transaksi_po_detail').datagrid({
        onLoadSuccess   : function(){
            $('#transaksi_po_detail-new').linkbutton('enable');
            $('#transaksi_po_detail-upload').linkbutton('enable');
            $('#transaksi_po_detail-refresh').linkbutton('enable');
            $('#transaksi_po_detail-edit').linkbutton('disable');
            $('#transaksi_po_detail-delete').linkbutton('disable');
            $('#transaksi_po_detail-generate_prod').linkbutton('disable');
            $('#transaksi_po_detail-view_lot').linkbutton('disable');
        },
        onSelect        : function(){
            $('#transaksi_po_detail-edit').linkbutton('enable');
            $('#transaksi_po_detail-delete').linkbutton('enable');
            $('#transaksi_po_detail-generate_prod').linkbutton('enable');
            $('#transaksi_po_detail-view_lot').linkbutton('enable');
        },
        onClickRow      : function(index,row){
            $('#transaksi_po_detail-edit').linkbutton('enable');
            $('#transaksi_po_detail-delete').linkbutton('enable');
            $('#transaksi_po_detail-generate_prod').linkbutton('enable');
            $('#transaksi_po_detail-view_lot').linkbutton('enable');
        },
        onDblClickRow   : function(){
            transaksiPoDetailUpdate();
        },
        view            :scrollview,
        remoteFilter    :true})
    .datagrid('enableFilter');
    
    function transaksiPoDetailRefresh() {
        $('#transaksi_po_detail-edit').linkbutton('disable');
        $('#transaksi_po_detail-delete').linkbutton('disable');
        $('#transaksi_po_detail-generate_prod').linkbutton('disable');
        $('#transaksi_po_detail-view_lot').linkbutton('disable');
        $('#grid-transaksi_po_detail').datagrid('reload');
    }
    
    function transaksiPoDetailCreate() {
        var row = $('#grid-transaksi_po').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_po_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
            $('#fm-transaksi_po_detail').form('clear');
            url = '<?php echo site_url('transaksi/po/detailCreate'); ?>';
            $('#t_po_detail_no').textbox('setValue',row.t_po_header_no);
            $('#t_po_detail_date').datebox('setValue',row.t_po_header_date);
            $('#t_po_detail_lot_no').textbox('enable');
        }
        else{
            $.messager.alert('Info','Header belum dipilih !','info');
        }
        
    }
    
    function transaksiPoDetailUpdate() {
        var row1 = $('#grid-transaksi_po').datagrid('getSelected');
        if(row1){
            var row2 = $('#grid-transaksi_po_detail').datagrid('getSelected');
            if(row2){
                $('#dlg-transaksi_po_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
                $('#fm-transaksi_po_detail').form('load',row2);
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
    
    function transaksiPoDetailSave(){
        $('#fm-transaksi_po_detail').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-transaksi_po_detail').dialog('close');
                    transaksiPoDetailRefresh();
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
    
    function transaksiPoDetailHapus(){
        var row = $('#grid-transaksi_po_detail').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus LOT \n'+row.t_po_detail_lot_no+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/po/detailDelete'); ?>',{t_po_detail_lot_no:row.t_po_detail_lot_no},function(result){
                        if (result.success)
                        {
                            transaksiPoDetailRefresh();
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
    
    function transaksiPoDetailUpload()
    {
        $('#dlg-transaksi_po_detail-upload').dialog({modal: true}).dialog('open').dialog('setTitle','Upload File');
        $('#fm-transaksi_po_detail-upload').form('reset');
        urls = '<?php echo site_url('transaksi/po/detailUpload'); ?>/';
    }
    
    function transaksiPoDetailUploadSave()
    {
        $('#fm-transaksi_po_detail-upload').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success)
                {
                    $('#dlg-transaksi_po_detail-upload').dialog('close');
                    transaksiPoDetailRefresh();
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
        var row = $('#grid-transaksi_po_detail').datagrid('getSelected');
        if (row){
            $.messager.progress({
                title   :'Please waiting',
                msg     :'Executing...'
            });
            $.post('<?php echo site_url('transaksi/po/detailGenerateProd'); ?>',{item_id:row.t_po_detail_item, prod_qty:row.t_po_detail_prod, lot:row.t_po_detail_lot_no},function(result){
            if (result.success)
            {
                transaksiPoDetailRefresh();
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

<div id="dlg-transaksi_po_detail-upload" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-transaksi_po_detail-upload">
    <form id="fm-transaksi_po_detail-upload" method="post" enctype="multipart/form-data" novalidate>       
        <div class="fitem">
            <label for="type">File</label>
            <input id="detailPo" name="detailPo" class="easyui-filebox" required="true"/>
        </div> 
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-transaksi_po_detail-upload">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiPoDetailUploadSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_po_detail-upload').dialog('close');">Batal</a>
</div>


<!-- ----------- -->
<div id="dlg-transaksi_po_detail" class="easyui-dialog" style="width:500px; height:400px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_po_detail">
    <form id="fm-transaksi_po_detail" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nomor PO</label>
            <input type="text" id="t_po_detail_no" name="t_po_detail_no" class="easyui-textbox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tanggal PO</label>
            <input type="text" id="t_po_detail_date" name="t_po_detail_date" class="easyui-datebox" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">LOT</label>
            <input type="text" id="t_po_detail_lot_no" name="t_po_detail_lot_no" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Cust.</label>
            <input type="text" id="t_po_detail_cust" name="t_po_detail_cust" style="width:250px;" class="easyui-combobox" required="true"
                data-options="
                method:'get', valueField:'m_cust_id', textField:'m_cust_name', panelHeight:'150',
                onShowPanel: function(){
                    var url = '<?php echo site_url('transaksi/po/getCust'); ?>';
                    $('#t_po_detail_cust').combobox('reload', url);
                }"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="t_po_detail_item" name="t_po_detail_item" style="width:250px;" class="easyui-combobox" required="true"
                data-options="
                method:'get', valueField:'m_item_id', textField:'m_item_name', 
                onShowPanel: function(){
                    var url = '<?php echo site_url('transaksi/po/getItem'); ?>';
                    $('#t_po_detail_item').combobox('reload', url);
                },
                onSelect: function(){
                    $('#t_po_detail_qty').numberbox('setValue', '');
                    $('#t_po_detail_prod').numberbox('setValue', '');
                },panelHeight:'150'"/>
        </div>        
        <div class="fitem">
            <label for="type">Qty PO</label>
            <input type="text" id="t_po_detail_qty" name="t_po_detail_qty" class="easyui-numberbox" required="true"/>
            <a id="button_a-t_po_detail_qty" href="javascript:calcProdQty()" class="easyui-linkbutton easyui-tooltip"  
                    title="Calculate Qty. Prod." iconCls="icon-calculator" plain="true" data-options="position:'right'" onclick=""></a>
        </div>
        <div class="fitem">
            <label for="type">Qty Prod.</label>
            <input type="text" id="t_po_detail_prod" name="t_po_detail_prod" class="easyui-numberbox" required="true"/>
        </div>        
        <div class="fitem">
            <label for="type">Tgl. Prod.</label>
            <input type="text" id="t_po_detail_prod_date" name="t_po_detail_prod_date" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tgl. Delv.</label>
            <input type="text" id="t_po_detail_delv_date" name="t_po_detail_delv_date" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Berat Produksi</label>
            <input type="text" id="t_po_detail_prod_weight" name="t_po_detail_prod_weight" class="easyui-numberbox" precision="1" required="true"/>
        </div>       
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_po_detail">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiPoDetailSave();">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_po_detail').dialog('close');">Batal</a>
</div>

<!-- View LOT -->
<script type="text/javascript">
    //var cinta;
    function transaksiViewLot(){
        var row = $('#grid-transaksi_po_detail').datagrid('getSelected');
        if (row)
        {
            $('#dlg-transaksi_po_lot').dialog({
                title   : 'LOT - '+row.t_po_detail_lot_no,
                width   : 400,
                height  : 400,
                modal   : true
            });
            $('#dlg-transaksi_po_lot').dialog('refresh', $('#grid-transaksi_po_lot').datagrid('load', '<?php echo site_url('transaksi/po/lot'); ?>?grid=true&nilailot='+row.t_po_detail_lot_no));
        }
    }
    
    var toolbar_transaksi_po_lot = [{
        id      : 'transaksi_po_lot-print_all',
        text    : 'Print All',
        iconCls : 'icon-print',
        handler : function(){transaksiPoLotPrintAll();}
    },{
        id      : 'transaksi_po_lot-print_sublot',
        text    : 'Print SubLot',
        iconCls : 'icon-print',
        handler : function(){transaksiPoLotPrintSublot();}
    },{
        id      : 'transaksi_po_lot-print_selected',
        text    : 'Print Selected',
        iconCls : 'icon-print',
        handler : function(){transaksiPoLotPrintSelected();}
    }];

    $('#grid-transaksi_po_lot').datagrid({        
        view            :scrollview,
        remoteFilter    :true})
    .datagrid('enableFilter');

    function transaksiPoLotPrintAll() {
        var row = $('#grid-transaksi_po_detail').datagrid('getSelected');
        if (row)
        {
            var url = '<?php echo site_url('transaksi/po/printAll'); ?>/' + row.t_po_detail_lot_no;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-transaksi_po_card_print').dialog({
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
    
    function transaksiPoLotPrintSublot() {
        var row = $('#grid-transaksi_po_lot').datagrid('getSelected');
        if (row)
        {
            var url = '<?php echo site_url('transaksi/po/printSublot'); ?>?lot='+row.t_prod_lot+'&sublot='+ row.t_prod_sublot;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-transaksi_po_card_print').dialog({
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
    
    function transaksiPoLotPrintSelected() {
        var row = $('#grid-transaksi_po_lot').datagrid('getSelected');
        if (row)
        {
            var url = '<?php echo site_url('transaksi/po/printSelected'); ?>/' + row.t_prod_id;
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#dlg-transaksi_po_card_print').dialog({
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

<div id="dlg-transaksi_po_lot">
    <table id="grid-transaksi_po_lot"
        data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                    fit:true, fitColumns:true, toolbar:toolbar_transaksi_po_lot">
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

<div id="dlg-transaksi_po_card_print">
    
</div>
<!-- End of file v_po.php -->
<!-- Location: ./application/views/transaksi/v_po.php -->