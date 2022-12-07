<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets"); ?>/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets"); ?>/easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/jquery.easyui.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/datagrid-detailview.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/jquery.edatagrid.js"></script> -->
<script type="text/javascript" src="<?php echo base_url('assets/js/script_group.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/easyuidgvclientpaging.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var esok = new Date();
        esok.setDate(esok.getDate() + 1);
        // alert(d);
        var esok2 = new Date();
        esok2.setDate(esok2.getDate() + 2);
        $('.date-picker').datepicker({
            autoclose: true,
            responsive: true,
            format: "dd-mm-yyyy",
            todayHighlight: true,
            todayBtn: true,
            todayHighlight: true,
            startDate: esok,
            endDate: esok2
        });
        $(".date-picker").datepicker("update", esok);

        $('#dgGroup').datagrid({
            url: '<?php echo site_url('sistem/admin/group/getGroup') ?>',
            columns: [
                [{
                        field: 'role_cd',
                        title: 'Kode',
                        width: '30%'
                    },
                    {
                        field: 'role_nm',
                        title: 'Nama Group',
                        width: '40%'
                    }
                ]
            ]
        }).datagrid('clientPaging');


    });
</script>
<!-- <div class="row"> -->
<section class="content-header">
    <h1>
        <?= $title; ?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>depan"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<section class="content">

    <div class="box">
        <div class="box-body">
            <table id="dgGroup" class="easyui-datagrid" style="width:96%;height:440px" title="Data Group/Role" rownumbers="true" pagination="true" fitColumns="true" singleSelect="true" toolbar="#tb2" pageSize="25" pageList="[25,50,75,100,125,150,200]" collapsible="true">
            </table>
            <div></div>

            <div id="tb2">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onClick="newGroup()">New</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onClick="editGroup()">Edit</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onClick="destroyGroup()">Destroy</a>
                <input id="searchGroup" class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:doSearchGroup,
            inputEvents: $.extend({}, $.fn.searchbox.defaults.inputEvents, {
                keyup: function(e){
                    var t = $(e.data.target);
                    var opts = t.searchbox('options');
                    t.searchbox('setValue', $(this).val());
                    opts.searcher.call(t[0],t.searchbox('getValue'),t.searchbox('getName'));
                }
              })" style="width:50%;"></input>
            </div>

        </div>
        <div class="box-footer clearfix">

        </div>
    </div>


</section>
<div id="dlgGroup" class="easyui-dialog" style="width: 500px; height: auto; padding: 10px ;" modal="true" closed="true" buttons="#dlgGroupBtn">
    <form id="fmGroup" method="post">
        <div style="margin-bottom:20px">
            <input class="easyui-textbox" name="role_cd" style="width:70%" data-options="label:'Kode:',required:true">
        </div>
        <div style="margin-bottom:20px">
            <input class="easyui-textbox" name="role_nm" style="width:90%" data-options="label:'Nama:',required:true">
        </div>
        
    </form>
    </form>
</div>
<div id="dlgGroupBtn">
    <a href="javascript:void(0)" id="btn_save" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveGroup()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-close" onclick="javascript:$('#dlgGroup').dialog('close'); $('#fmGroup').form(clear)
        " style="width:90px">Cancel</a>
</div>
<!-- </div> -->


<!-- <h2>Basic Form</h2>
  <p>Fill the form and submit it.</p>
  <div style="margin:20px 0;"></div>
  <div class="easyui-panel" title="New Topic" style="width:100%;max-width:400px;padding:30px 60px;">
    <form id="ff" method="post">
      <div style="margin-bottom:20px">
        <input class="easyui-textbox" name="name" style="width:100%" data-options="label:'Name:',required:true">
      </div>
      <div style="margin-bottom:20px">
        <input class="easyui-textbox" name="email" style="width:100%" data-options="label:'Email:',required:true,validType:'email'">
      </div>
      <div style="margin-bottom:20px">
        <input class="easyui-textbox" name="subject" style="width:100%" data-options="label:'Subject:',required:true">
      </div>
      <div style="margin-bottom:20px">
        <input class="easyui-textbox" name="message" style="width:100%;height:60px" data-options="label:'Message:',multiline:true">
      </div>
      <div style="margin-bottom:20px">
        <select class="easyui-combobox" name="language" label="Language" style="width:100%"><option value="ar">Arabic</option><option value="bg">Bulgarian</option><option value="ca">Catalan</option><option value="zh-cht">Chinese Traditional</option><option value="cs">Czech</option><option value="da">Danish</option><option value="nl">Dutch</option><option value="en" selected="selected">English</option><option value="et">Estonian</option><option value="fi">Finnish</option><option value="fr">French</option><option value="de">German</option><option value="el">Greek</option><option value="ht">Haitian Creole</option><option value="he">Hebrew</option><option value="hi">Hindi</option><option value="mww">Hmong Daw</option><option value="hu">Hungarian</option><option value="id">Indonesian</option><option value="it">Italian</option><option value="ja">Japanese</option><option value="ko">Korean</option><option value="lv">Latvian</option><option value="lt">Lithuanian</option><option value="no">Norwegian</option><option value="fa">Persian</option><option value="pl">Polish</option><option value="pt">Portuguese</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="es">Spanish</option><option value="sv">Swedish</option><option value="th">Thai</option><option value="tr">Turkish</option><option value="uk">Ukrainian</option><option value="vi">Vietnamese</option></select>
      </div>
    </form>
    <div style="text-align:center;padding:5px 0">
      <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()" style="width:80px">Submit</a>
      <a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()" style="width:80px">Clear</a>
    </div>
  </div>
  <script>
   
    function submitForm(){
      $('#ff').form('submit');
    }
    function clearForm(){
      $('#ff').form('clear');
    }
  </script> -->