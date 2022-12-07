var ttl;
function doSearchGroup(){
	$('#dgGroup').datagrid('load',{
		search_group: $('#searchGroup').val()
	});
}

function newGroup() {
	$('#dlgGroup').dialog('open').dialog('setTitle','Tambah Group');
	$('#fmGroup').form('clear');
	url = 'sistem/admin/group/saveGroup';
	ttl = "new";
}

function editGroup() {
	var row = $('#dgGroup').datagrid('getSelected');
	if (row){
		$('#dlgGroup').dialog('open').dialog('setTitle','Edit');
		$('#fmGroup').form('load',row);
		url = 'sistem/admin/group/updateGroup/'+row.role_cd;
		ttl = "updt";
	}else {
		$.messager.alert('Warning','Tidak ada data yang dipilih !!!');
	}
}

function saveGroup() {
	$('#fmGroup').form('submit',{
		url: url,
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var result = eval('(' + result + ')');
			if (result.errorMsg){
				$.messager.show({
					title: 'Error',
					msg: result.errorMsg
				});
			}else if (result.success){
                $('#dlgGroup').dialog('close');		// close the dialog
				$('#dgGroup').datagrid('reload');	// reload the user data
                $('#fmGroup').form('clear');
                var opts = $('#dgGroup').datagrid('getColumnFields', true);
                var msg = ttl == "updt" ? "Update data berhasil" : "Data baru berhasil ditambahkan";
                var title = ttl == "updt" ? "Data Update" : "Data Baru";
                $.messager.alert({
                    title: title,
                    msg: msg,
                    fadeOut: 'slow',
                    showType:'fade',
                });
            }else {
				 $.messager.alert({
                        title: 'Error',
                        msg: "Encourage Error!"
                    });
			}
		}
	});
}

function destroyGroup() {
	var row = $('#dgGroup').datagrid('getSelected');
	// var id=row.role_cd;
	if (row){
		$.messager.confirm('Konfirmasi','Yakin akan menghapus data ini..? Data akan dihapus dari database',function(r){
			if (r){
				$.post('sistem/admin/group/destroyGroup',{id:row.role_cd},function(result){
					if (result.success){
						$('#dgGroup').datagrid('reload');	// reload the Vendor data
					} else {
						$.messager.show({	// show error message
							title: 'Error',
							msg: result.errorMsg
						});
					}
				},'json');
				
			}
		}
		);
	}
	// alert(row.role_cd);
}