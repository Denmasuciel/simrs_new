<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets"); ?>/easyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets"); ?>/easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/jquery.easyui.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/datagrid-detailview.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets"); ?>/easyui/jquery.edatagrid.js"></script> -->
<script type="text/javascript" src="<?php echo base_url('assets/js/script.js') ?>"></script>
<script type="text/javascript">
  function apiwa() {
    $('#wahariini').DataTable({
      "bProcessing": true,
      // "scrollY": 350,
      "scrollX": true,
      "scrollCollapse": true,
      "bServerside": true,
      "sAjaxSource": "<?php echo site_url('depan/apilistranap'); ?>",
      "fnServerData": function(sSource, aoData, fnCallback) {
        $.ajax({
          "dataType": 'json',
          "type": "POST",
          "url": sSource,
          "success": fnCallback
        });
      },
      "columns": [{
          "data": "no"
        },
        // {
        //   "data": "no_rm"
        // },
        {
          "data": "pasien_nm"
        },
        {
          "data": "alamat"
        },
        {
          "data": "bangsal_nm"
        },
        {
          "data": "ruang_nm"
        },
        // {
        //   "data": "dr_nm"
        // },
        {
          "data": "tgl_masuk"
        }
      ],
      "footerCallback": function(row, data, start, end, display) {}
    });
  };


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
    apiwa();

    $('#tt').datagrid({
      url: '<?php echo site_url('depan/apilistranapeasyui') ?>',
      columns: [
        [{
            field: 'pasien_nm',
            title: 'Kode',
            width: '20%'
          },
          {
            field: 'address',
            title: 'Uraian',
            width: '30%'
          },
          {
            field: 'bangsal_nm',
            title: 'Tipe',
            width: '20%'
          },
          {
            field: 'ruang_nm',
            title: 'Tipe',
            width: '20%'
          },
          {
            field: 'datetime_in',
            title: 'Tipe',
            width: '10%'
          }
        ]
      ]
    }).datagrid('clientPaging');

    $('#dgCustomers').datagrid({
      url: '<?php echo site_url('depan/getdata') ?>',
      columns: [
        [{
            field: 'pasien_nm',
            title: 'Kode',
            width: '20%'
          },
          {
            field: 'no_rm',
            title: 'Uraian',
            width: '30%'
          },
          {
            field: 'address',
            title: 'Tipe',
            width: '20%'
          },
          {
            field: 'phone',
            title: 'Tipe',
            width: '20%'
          }
        ]
      ]
    }).datagrid('clientPaging');

    function doSearch(value) {
      $('#tt').datagrid('load', {
        // pasien_nm: value
      });
    }
    // function caridisposisi(value) {
    //     $('#dg_disposisi').datagrid('load', {
    //         caridisposisi: value
    //     });
    // }
    // $('#tre_menu').tree('collapseAll');
    collapseAll();
  });
  function collapseAll(){
			$('#tt').tree('collapseAll');
		}
  // function reload_table() {
  //   $('#wahariini').dataTable().fnDestroy();
  //   apiwa();
  // }

  // function wa_cetak() {
  //         // var tanggal = $("#datepicker1").val();
  //         window.open('<?php echo site_url('depan/antrian_wa_cetak'); ?>/' );
  //     }
  (function($) {
    function pagerFilter(data) {
      if ($.isArray(data)) { // is array
        data = {
          total: data.length,
          rows: data
        }
      }
      var target = this;
      var dg = $(target);
      var state = dg.data('datagrid');
      var opts = dg.datagrid('options');
      if (!state.allRows) {
        state.allRows = (data.rows);
      }
      if (!opts.remoteSort && opts.sortName) {
        var names = opts.sortName.split(',');
        var orders = opts.sortOrder.split(',');
        state.allRows.sort(function(r1, r2) {
          var r = 0;
          for (var i = 0; i < names.length; i++) {
            var sn = names[i];
            var so = orders[i];
            var col = $(target).datagrid('getColumnOption', sn);
            var sortFunc = col.sorter || function(a, b) {
              return a == b ? 0 : (a > b ? 1 : -1);
            };
            r = sortFunc(r1[sn], r2[sn]) * (so == 'asc' ? 1 : -1);
            if (r != 0) {
              return r;
            }
          }
          return r;
        });
      }
      var start = (opts.pageNumber - 1) * parseInt(opts.pageSize);
      var end = start + parseInt(opts.pageSize);
      // data.rows = state.allRows.slice(start, end);
      return data;
    }

    var loadDataMethod = $.fn.datagrid.methods.loadData;
    var deleteRowMethod = $.fn.datagrid.methods.deleteRow;
    $.extend($.fn.datagrid.methods, {
      clientPaging: function(jq) {
        return jq.each(function() {
          var dg = $(this);
          var state = dg.data('datagrid');
          var opts = state.options;
          opts.loadFilter = pagerFilter;
          var onBeforeLoad = opts.onBeforeLoad;
          opts.onBeforeLoad = function(param) {
            state.allRows = null;
            return onBeforeLoad.call(this, param);
          }
          var pager = dg.datagrid('getPager');
          pager.pagination({
            onSelectPage: function(pageNum, pageSize) {
              opts.pageNumber = pageNum;
              opts.pageSize = pageSize;
              pager.pagination('refresh', {
                pageNumber: pageNum,
                pageSize: pageSize
              });
              dg.datagrid('loadData', state.allRows);
            }
          });
          $(this).datagrid('loadData', state.data);
          if (opts.url) {
            $(this).datagrid('reload');
          }
        });
      },
      loadData: function(jq, data) {
        jq.each(function() {
          $(this).data('datagrid').allRows = null;
        });
        return loadDataMethod.call($.fn.datagrid.methods, jq, data);
      },
      deleteRow: function(jq, index) {
        return jq.each(function() {
          var row = $(this).datagrid('getRows')[index];
          deleteRowMethod.call($.fn.datagrid.methods, $(this), index);
          var state = $(this).data('datagrid');
          if (state.options.loadFilter == pagerFilter) {
            for (var i = 0; i < state.allRows.length; i++) {
              if (state.allRows[i] == row) {
                state.allRows.splice(i, 1);
                break;
              }
            }
            $(this).datagrid('loadData', state.allRows);
          }
        });
      },
      getAllRows: function(jq) {
        return jq.data('datagrid').allRows;
      }
    })
  })(jQuery);
</script>
<div class="row">
  <section class="content">
    <div class="box">
      <!-- <div class="box-header with-border">
        <i class="fa fa-wheelchair"></i>
        <h3 class="box-title"> Antrian Pendaftaran WA hari ini <b></b></h3>
        <div class="margin pull-right">
          <div class="btn-group">
            <button type="button" class="btn btn-info" onclick="reload_table()"><i class="fa fa-refresh">&nbsp;</i>Refresh Tabel</button>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-warning" id="btn_simpan" onclick="wa_cetak()"><i class="fa fa-print">&nbsp;</i>Cetak</button>
          </div>
        </div>
      </div> -->
      <div class="box-body">
        <div style="margin:10px 0;"></div>
        <div class="easyui-panel" style="padding:5px">
          <ul id="tre_menu" class="easyui-tree" data-options="url:'<?php echo site_url('depan/getmenu'); ?>',method:'get',animate:true,lines:true"></ul>
        </div>
        <!-- <table id="dgCustomers" toolbar="#toolbarCustomer" cstyle="width:100%;height:440px" title="Data Pasien" 
      rownumbers="true" pagination="true" fitColumns="true" singleSelect="true" pageSize="25" pageList="[25,50,75,100,125,150,200]" collapsible="true">         
        </table> -->
        <table id="dgCustomers" class="easyui-datagrid" style="width:96%;height:440px" title="Data Pasien Ranap" rownumbers="true" pagination="true" fitColumns="true" singleSelect="true" toolbar="#tb2" pageSize="25" pageList="[25,50,75,100,125,150,200]" collapsible="true">
        </table>
        <div></div>

        <div id="tb2">
          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onClick="newCustomer()">New</a>
          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onClick="editCustomer()">Edit</a>
          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onClick="destroyCustomer()">Destroy</a>
          <input id="searchCustomer" class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:doSearchCustomer,
            inputEvents: $.extend({}, $.fn.searchbox.defaults.inputEvents, {
                keyup: function(e){
                    var t = $(e.data.target);
                    var opts = t.searchbox('options');
                    t.searchbox('setValue', $(this).val());
                    opts.searcher.call(t[0],t.searchbox('getValue'),t.searchbox('getName'));
                }
              })" style="width:50%;"></input>
        </div>
        </p>
        <div id="tb" style="padding:3px">
          <a href="#" class="easyui-linkbutton new" iconCls="icon-add" plain="true">New</a>
          <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="">Edit</a>
          <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="">Delete</a><br>
          <span>Pencarian:</span>
          <input id="urai" style="line-height:16px;border:1px solid #ccc; width:200px" onkeyup="doSearch()">
        </div>

        <table id="tt" class="easyui-datagrid" style="width:96%;height:440px" title="Data Pasien Ranap" rownumbers="true" pagination="true" fitColumns="true" singleSelect="true" toolbar="#tb" pageSize="25" pageList="[25,50,75,100,125,150,200]" collapsible="true">
        </table>
        <div></div>

        <!-- <table id="wahariini" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="5%">No</th>
             <th>Nama</th>
              <th width="20%">Alamat</th>
              <th>Bangsal</th>
              <th>Ruang</th>
              <th width="10%">Tanggal Masuk</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table> -->
      </div>
      <div class="box-footer clearfix">

      </div>
    </div>

    <p>&nbsp;</p>
    <!-- <div region="center"> -->

  </section>
  <div id="dlgCustomer" class="easyui-dialog" style="width: 780px; height: auto; padding: 10px;" modal="true" closed="true" buttons="#dlgCustomerBtn">
    <form id="fmCustomer" method="post">
      <div class="col-sm-12 justify-content-sm-center">
        <div class="row" style="width: 100%">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Customer Name</label>
              <input type="text" name="customerName" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Contact First Name</label>
              <input type="text" name="contactFirstName" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Contract Last Name</label>
              <input type="text" name="contactLastName" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Phone</label>
              <input type="text" name="phone" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
        </div>
        <div class="row" style="width: 100%">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">First Address Line</label>
              <input type="text" name="addressLine1" multiline="true" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Second Address Line</label>
              <input type="text" name="addressLine2" multiline="true" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">City</label>
              <input type="text" name="city" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">State</label>
              <input type="text" name="state" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
        </div>
        <div class="row" style="width: 100%">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Postal Code</label>
              <input type="text" name="postalCode" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="">Country</label>
              <input type="text" name="country" class="easyui-textbox" style="width: 100%;">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div id="dlgCustomerBtn">
    <a href="javascript:void(0)" id="btn_save" class="easyui-linkbutton" iconCls="icon-ok-a" onclick="saveCustomer()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-del-a" onclick="javascript:$('#dlgCustomer').dialog('close'); $('#fmEmployee').form(clear)
        " style="width:90px">Cancel</a>
  </div>
</div>
<br />


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