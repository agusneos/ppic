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
<style type="text/css">
    #fm-dialog_rekap{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_rekap{
        margin:0;
        padding:10px 100px;
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
</style>
<!-- Form -->
    <form id="fm-dialog_rekap" method="post" novalidate buttons="#dlg_btn-dialog_rekap">
        <div class="fitem">
            <label for="type">Proses</label>
            <input type="text" id="rekap_proses" name="rekap_proses" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('inquiry/rekap/getProces'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Jenis</label>
            <select  type="text" id="rekap_jenis" name="rekap_jenis" style="width:150px;" data-options="panelHeight:'50'" class="easyui-combobox" required="true">
                <option value="0"></option>
                <option value="1">Rekap. Barang</option>
                <option value="2">Rekap. Mesin</option>
            </select>
        </div>
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input  id="rekap_tgl" name="rekap_tgl" class="easyui-datebox" style="width:150px;" required="true">
        </div>
        <div class="fitem">
            <label for="type">Periode</label>
            <select  type="text" id="rekap_periode" name="rekap_periode" style="width:150px;" data-options="panelHeight:'100'" class="easyui-combobox" required="true">
                <option value="0"></option>
                <option value="1">Harian</option>
                <option value="2">Mingguan</option>
                <option value="3">Bulanan</option>
                <option value="4">Tahunan</option>
            </select>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_rekap">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="show_rekap();">Show</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>

<script type="text/javascript">
    function show_rekap(){
        var isValid = $('#fm-dialog_rekap').form('validate');
        if (isValid){
            var rekap_jenis = $('#rekap_jenis').combobox('getValue');
            rekap_proses    = $('#rekap_proses').combobox('getValue');
            rekap_tgl       = $('#rekap_tgl').datebox('getValue');
            rekap_periode   = $('#rekap_periode').combobox('getValue');
            var url         = '';
            if (rekap_jenis==1){
                url         = '<?php echo site_url('inquiry/rekap/showRekapBarang'); ?>?rekap_proses='+rekap_proses+'&rekap_tgl='+rekap_tgl+'&rekap_periode='+rekap_periode;
            }
            else{
                url         = '<?php echo site_url('inquiry/rekap/showRekapMesin'); ?>?rekap_proses='+rekap_proses+'&rekap_tgl='+rekap_tgl+'&rekap_periode='+rekap_periode;
            }            
            var title       = 'Rekapitulasi Transaksi';

            $('#tt').tabs('close', title);
            $('#tt').tabs('add',{
                title   : title,                    
                href    : url,
                closable: true,
                iconCls : 'icon-search-2'
            });
            $('#dlg').dialog('close');
        }          
    }
</script>

<!-- End of file v_dialog_rekap.php -->
<!-- Location: ./views/inquiry/hutang_supplier/v_dialog_rekap.php -->
