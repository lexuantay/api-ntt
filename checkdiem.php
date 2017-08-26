<?php
header('Content-Type: application/json; charset=utf-8');
$mssv= $_GET['mssv'];
$type= $_GET['type'];
if($type == "checkdiem")
{
	$web='http://phongdaotao2.ntt.edu.vn/XemDiem.aspx?MSSV='.$mssv;
	$url = file_get_contents($web);
	$patten = '#<table width="100%" class="grid grid-color2 tblKetQuaHocTap">(.*?)<\/table>#is';
	preg_match($patten,$url,$ketqua1,PREG_OFFSET_CAPTURE);
	$patten2 = '/b>(.*?)<\/b>/';
	preg_match_all($patten2,@$ketqua1[0][0],$ketqua2,PREG_SET_ORDER,0);
	$arr = array (
	'messages' => 
	array (
		0 => 
		array (
		'text' => 'Tổng Tín Chỉ : '.@$ketqua2[0][1],
		),
		1 => 
		array (
		'text' => 'Trung bình chung tích lũy: '.@$ketqua2[1][1],
		),
		2 => 
		array (
		'text' => 'Tổng tín chỉ nợ : '.@$ketqua2[2][1],
		),
	),
	);
	echo json_encode($arr);
}
if($type == "checkinfo")
{
	$web1='http://phongdaotao2.ntt.edu.vn/CongNoSinhVien.aspx?MSSV='.$mssv;
	$url1 = file_get_contents($web1);
	$par_hoten = '/<br \/>
            (.*?)<\/div>/mu';

$par_lop = '/<td>
                    Lớp:
                    (.*?)
                <\/td>/mu';
				
$par_nganh = '/<td>
                    Ngành:
                    (.*?)
                <\/td>/mu';
				
$par_khoa = '/<td>
                    Khóa:
                    (.*?)
                <\/td>/mu';
				
$par_chucvu = '@<td>
                    Chức vụ:
                    (.*?)
                </td>@';
$par_tcongno = '/<span style="color: Red;">
                            (.*?)
                            VNĐ<\/span>/mu';
	preg_match_all($par_tcongno,$url1,$tcongno,PREG_SET_ORDER,0);			
	preg_match($par_khoa,$url1,$khoa,PREG_OFFSET_CAPTURE,0);
	preg_match($par_nganh,$url1,$nganh,PREG_OFFSET_CAPTURE,0);
	preg_match($par_lop,$url1,$lop,PREG_OFFSET_CAPTURE,0);
	preg_match($par_hoten,$url1,$hoten,PREG_OFFSET_CAPTURE,0);
	preg_match($par_chucvu,$url1,$chucvu,PREG_OFFSET_CAPTURE,0);
	if(@$chucvu[1][0] =='')
	{
		$chucvu='Sinh viên';
	}else
	{
		$chucvu=@$chucvu[1][0];
	}
					
	$arr1 = array (
	  'messages' => 
	  array (
		0 => 
		array (
		  'text' => 'Xin Chào : '.@$hoten[1][0].' Lớp '.@$lop[1][0],
		),
		1 => 
		array (
		  'text' => 'Ngành : '.@$nganh[1][0].' Khóa '.@$khoa[1][0],
		),
		2 => 
		array (
		  'text' => 'Chức Vụ : '.@$chucvu,
		),
		3 => 
		array (
		  'text' => 'Tổng Công Nợ Hiện Tại : '.@$tcongno[0][1],
		),
	  ),
	);
	echo json_encode($arr1);
}
if($type == "checkcn")
{
	$web1='http://phongdaotao2.ntt.edu.vn/CongNoSinhVien.aspx?MSSV='.$mssv;
$url1 = file_get_contents($web1);
$par_congno = '@<td style="border-left: none">
                    \d
                </td>
                
                <td>
                    (.*?)
                </td>
                <td>
                    (.*?)
                    
                </td>
                <td>
                    (.*?)
                </td>
                
                <td class="text-right">
                    \d+,\d+,\d+
                </td>
                <td class="text-right">
                    \d
                </td>
                <td class="text-right">
                    (.*?)
                </td>
                <td class="text-right">
                    (.*?)
                </td>
                
                   
                <td style="border-right: none">
                    Chưa nộp
                </td>
            </tr>@';
	
	$cout = preg_match_all($par_congno,$url1,$congno,PREG_SET_ORDER,0);

	$messages = array();
	$messages['messages'] = [];
	for($i = 0;$i<$cout;$i++){
		$value = [];
		@$value['text'] = @'Mã học phần : '.$congno[$i][1].'\nNội dung thu : '.$congno[$i][2].'\nTín chỉ : '.$congno[$i][3].'\nKhấu trừ : '.$congno[$i][4].'\nCông nợ : '.$congno[$i][5];
		$messages['messages'][] = $value;
	}
	$a = json_encode($messages,JSON_UNESCAPED_UNICODE);
	$b  = preg_replace('@\\\\n@','n',$a);
	echo $b;

}
if($type =="xemlichthi")
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"http://phongdaotao2.ntt.edu.vn/XemLichThi.aspx?MenuID=353");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
				'__EVENTTARGET=&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE=%2FwEPDwUKMTE0NzM4Mzc3Nw9kFgJmD2QWAgIBD2QWBgIBD2QWBGYPEGRkFgECAWQCAQ8PFgIeB1Zpc2libGVoZGQCBQ8QZA8WbGYCAQICAgMCBAIFAgYCBwIIAgkCCgILAgwCDQIOAg8CEAIRAhICEwIUAhUCFgIXAhgCGQIaAhsCHAIdAh4CHwIgAiECIgIjAiQCJQImAicCKAIpAioCKwIsAi0CLgIvAjACMQIyAjMCNAI1AjYCNwI4AjkCOgI7AjwCPQI%2BAj8CQAJBAkICQwJEAkUCRgJHAkgCSQJKAksCTAJNAk4CTwJQAlECUgJTAlQCVQJWAlcCWAJZAloCWwJcAl0CXgJfAmACYQJiAmMCZAJlAmYCZwJoAmkCagJrFmwQBQpU4bqldCBj4bqjBQItMWcQBQRLaG9hBQM0MDdnEAUPLS0tS2hvYSBExrDhu6NjBQM0MDhnEAUOR2nhu5tpIHRoaeG7h3UFAzM3N2cQBRdRdXkgY2jhur8gLSBRdXkgxJHhu4tuaAUDMzQ1ZxAFDC0tLVF1eSBjaOG6vwUDMzQ2ZxAFDi0tLVF1eSDEkeG7i25oBQMzNjRnEAUeLS0tQ2jGsMahbmcgdHLDrG5oIMSRw6BvIHThuqFvBQMzNDhnEAUULS0tQ2h14bqpbiDEkeG6p3UgcmEFAzM1MWcQBRhL4bq%2FIGhv4bqhY2ggxJHDoG8gdOG6oW8FAzM2NWcQBQ1HaeG6o25nIHZpw6puBQMzNjdnEAUJLS0tVXBkYXRlBQM0MDlnEAUKU2luaCB2acOqbgUDMzU3ZxAFFi0tLVPhu5UgdGF5IFNpbmggVmnDqm4FAzM3NmcQBQtUaMO0bmcgYsOhbwUDMzY4ZxAFDEJp4buDdSBt4bqrdQUDMzY5ZxAFDFtD4bqpbSBuYW5nXQUDMzc4ZxAFC1Row7RuZyBiw6FvBQMzNjhnEAUhVGjDtG5nIGLDoW8gZMOgbmggY2hvIEtow7NhIG3hu5tpBQM0MTRnEAUvVi92IMSQxINuZyBrw70gSOG7jWMgcGjhuqduLCBUS0IgdsOgIEjhu41jIHBow60FAzQwNGcQBRNWL3YgUXXhuqNuIGzDvSBIU1NWBQM0MTNnEAUSVi92IFThu5F0IG5naGnhu4dwBQM0MDNnEAUdVi92IFR1eeG7g24gc2luaCBMacOqbiB0aMO0bmcFAzQwNmcQBR5WL3YgVGjhu7FjIHThuq1wIC0gVmnhu4djIGzDoG0FAzQwNWcQBQlRdXkgY2jhur8FAzM0NmcQBQtRdXkgxJHhu4tuaAUDMzY0ZxAFEUNodeG6qW4gxJHhuqd1IHJhBQMzNTFnEAUKVOG6pXQgY%2BG6owUCLTFnEAUES2hvYQUDNDA3ZxAFDy0tLUtob2EgRMaw4bujYwUDNDA4ZxAFDkdp4bubaSB0aGnhu4d1BQMzNzdnEAUXUXV5IGNo4bq%2FIC0gUXV5IMSR4buLbmgFAzM0NWcQBQwtLS1RdXkgY2jhur8FAzM0NmcQBQ4tLS1RdXkgxJHhu4tuaAUDMzY0ZxAFHi0tLUNoxrDGoW5nIHRyw6xuaCDEkcOgbyB04bqhbwUDMzQ4ZxAFFC0tLUNodeG6qW4gxJHhuqd1IHJhBQMzNTFnEAUYS%2BG6vyBob%2BG6oWNoIMSRw6BvIHThuqFvBQMzNjVnEAUNR2nhuqNuZyB2acOqbgUDMzY3ZxAFCS0tLVVwZGF0ZQUDNDA5ZxAFClNpbmggdmnDqm4FAzM1N2cQBRYtLS1T4buVIHRheSBTaW5oIFZpw6puBQMzNzZnEAULVGjDtG5nIGLDoW8FAzM2OGcQBQxCaeG7g3UgbeG6q3UFAzM2OWcQBQxbQ%2BG6qW0gbmFuZ10FAzM3OGcQBQtUaMO0bmcgYsOhbwUDMzY4ZxAFIVRow7RuZyBiw6FvIGTDoG5oIGNobyBLaMOzYSBt4bubaQUDNDE0ZxAFL1YvdiDEkMSDbmcga8O9IEjhu41jIHBo4bqnbiwgVEtCIHbDoCBI4buNYyBwaMOtBQM0MDRnEAUTVi92IFF14bqjbiBsw70gSFNTVgUDNDEzZxAFElYvdiBU4buRdCBuZ2hp4buHcAUDNDAzZxAFHVYvdiBUdXnhu4NuIHNpbmggTGnDqm4gdGjDtG5nBQM0MDZnEAUeVi92IFRo4buxYyB04bqtcCAtIFZp4buHYyBsw6BtBQM0MDVnEAUJUXV5IGNo4bq%2FBQMzNDZnEAULUXV5IMSR4buLbmgFAzM2NGcQBRFDaHXhuqluIMSR4bqndSByYQUDMzUxZxAFClThuqV0IGPhuqMFAi0xZxAFBEtob2EFAzQwN2cQBQ8tLS1LaG9hIETGsOG7o2MFAzQwOGcQBQ5HaeG7m2kgdGhp4buHdQUDMzc3ZxAFF1F1eSBjaOG6vyAtIFF1eSDEkeG7i25oBQMzNDVnEAUMLS0tUXV5IGNo4bq%2FBQMzNDZnEAUOLS0tUXV5IMSR4buLbmgFAzM2NGcQBR4tLS1DaMawxqFuZyB0csOsbmggxJHDoG8gdOG6oW8FAzM0OGcQBRQtLS1DaHXhuqluIMSR4bqndSByYQUDMzUxZxAFGEvhur8gaG%2FhuqFjaCDEkcOgbyB04bqhbwUDMzY1ZxAFDUdp4bqjbmcgdmnDqm4FAzM2N2cQBQktLS1VcGRhdGUFAzQwOWcQBQpTaW5oIHZpw6puBQMzNTdnEAUWLS0tU%2BG7lSB0YXkgU2luaCBWacOqbgUDMzc2ZxAFC1Row7RuZyBiw6FvBQMzNjhnEAUMQmnhu4N1IG3huqt1BQMzNjlnEAUMW0PhuqltIG5hbmddBQMzNzhnEAULVGjDtG5nIGLDoW8FAzM2OGcQBSFUaMO0bmcgYsOhbyBkw6BuaCBjaG8gS2jDs2EgbeG7m2kFAzQxNGcQBS9WL3YgxJDEg25nIGvDvSBI4buNYyBwaOG6p24sIFRLQiB2w6AgSOG7jWMgcGjDrQUDNDA0ZxAFE1YvdiBRdeG6o24gbMO9IEhTU1YFAzQxM2cQBRJWL3YgVOG7kXQgbmdoaeG7h3AFAzQwM2cQBR1WL3YgVHV54buDbiBzaW5oIExpw6puIHRow7RuZwUDNDA2ZxAFHlYvdiBUaOG7sWMgdOG6rXAgLSBWaeG7h2MgbMOgbQUDNDA1ZxAFCVF1eSBjaOG6vwUDMzQ2ZxAFC1F1eSDEkeG7i25oBQMzNjRnEAURQ2h14bqpbiDEkeG6p3UgcmEFAzM1MWcQBQpU4bqldCBj4bqjBQItMWcQBQRLaG9hBQM0MDdnEAUPLS0tS2hvYSBExrDhu6NjBQM0MDhnEAUOR2nhu5tpIHRoaeG7h3UFAzM3N2cQBRdRdXkgY2jhur8gLSBRdXkgxJHhu4tuaAUDMzQ1ZxAFDC0tLVF1eSBjaOG6vwUDMzQ2ZxAFDi0tLVF1eSDEkeG7i25oBQMzNjRnEAUeLS0tQ2jGsMahbmcgdHLDrG5oIMSRw6BvIHThuqFvBQMzNDhnEAUULS0tQ2h14bqpbiDEkeG6p3UgcmEFAzM1MWcQBRhL4bq%2FIGhv4bqhY2ggxJHDoG8gdOG6oW8FAzM2NWcQBQ1HaeG6o25nIHZpw6puBQMzNjdnEAUJLS0tVXBkYXRlBQM0MDlnEAUKU2luaCB2acOqbgUDMzU3ZxAFFi0tLVPhu5UgdGF5IFNpbmggVmnDqm4FAzM3NmcQBQtUaMO0bmcgYsOhbwUDMzY4ZxAFDEJp4buDdSBt4bqrdQUDMzY5ZxAFDFtD4bqpbSBuYW5nXQUDMzc4ZxAFC1Row7RuZyBiw6FvBQMzNjhnEAUhVGjDtG5nIGLDoW8gZMOgbmggY2hvIEtow7NhIG3hu5tpBQM0MTRnEAUvVi92IMSQxINuZyBrw70gSOG7jWMgcGjhuqduLCBUS0IgdsOgIEjhu41jIHBow60FAzQwNGcQBRNWL3YgUXXhuqNuIGzDvSBIU1NWBQM0MTNnEAUSVi92IFThu5F0IG5naGnhu4dwBQM0MDNnEAUdVi92IFR1eeG7g24gc2luaCBMacOqbiB0aMO0bmcFAzQwNmcQBR5WL3YgVGjhu7FjIHThuq1wIC0gVmnhu4djIGzDoG0FAzQwNWcQBQlRdXkgY2jhur8FAzM0NmcQBQtRdXkgxJHhu4tuaAUDMzY0ZxAFEUNodeG6qW4gxJHhuqd1IHJhBQMzNTFnZGQCBw9kFgQCAw9kFgZmD2QWBAIDDxAPFgYeDURhdGFUZXh0RmllbGQFCFRlbkRvblZpHg5EYXRhVmFsdWVGaWVsZAUHSUREb25WaR4LXyFEYXRhQm91bmRnZBAVEApDxqEgc%2BG7nyAxCkPGoSBz4bufIDIKQ8ahIHPhu58gMwpDxqEgc%2BG7nyA0CkPGoSBz4bufIDUKQ8ahIHPhu58gNhdMacOqbiBr4bq%2FdCDEkOG7k25nIE5haRlMacOqbiBr4bq%2FdCDEkOG7k25nIFRow6FwGUxpw6puIGvhur90IELDrG5oIETGsMahbmcWTGnDqm4ga%2BG6v3QgxJDEg2sgTMSDaxRMacOqbiBr4bq%2FdCBBbiBHaWFuZxNMacOqbiBr4bq%2FdCBMb25nIEFuFUxpw6puIGvhur90IFPDoGkgR8OybhdMacOqbiBr4bq%2FdCBUw6J5IE5hbSDDgRZU4bqtcCDEkW%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%2FIhDwvmjcMqV2poNnVBy04eHK9AXUg%3D%3D&ctl00%24ucPhieuKhaoSat1%24RadioButtonList1=0&ctl00%24DdListMenu=-1&ctl00%24ContentPlaceHolder%24SearchType=radSinhVien&ctl00%24ContentPlaceHolder%24txtMSSV='.$mssv.'&ctl00%24ContentPlaceHolder%24cboHocKy3=36&ctl00%24ContentPlaceHolder%24TestType=radAllTest&ctl00%24ContentPlaceHolder%24btnSearch=Xem+l%E1%BB%8Bch+thi&ctl00%24ucRight1%24txtMaSV=&ctl00%24ucRight1%24txtMatKhau=&ctl00%24ucRight1%24rdSinhVien=1&txtSecurityCodeValue=f496d2a91073ebecbf10c701b8240f6c&ctl00%24ucRight1%24txtEncodeMatKhau=');
				
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	$par = '@<p>
                        (.*?)
                        <br />- (.*?)</p>
                </td>
                <td (.*?)>
                    <p>
                        (.*?)</p>
                </td>
                <td (.*?)>
                    <p>
                        (.*?)</p>
                </td>
                <td (.*?)>
                    <p>
                        (.*?)</p>
                </td>
                <td (.*?)>
                    <p>
                        (.*?)<br />
                        (.*?)</p>
                </td>
                
                <td (.*?)>
                    <p>
                        (.*?)
                        ->
                        (.*?)
                        (.*?)</p>
                </td>
                <td (.*?)>
                    <p>
                        (.*?)</p>
                </td>
                
                <td (.*?)>
                    
                    <p>
                        (.*?)</p>
                </td>
                
                <td (.*?)>
                    <p>
                        (.*?)</p>
                </td>
            </tr>@';
	$cout = preg_match_all($par, $server_output, $congno, PREG_SET_ORDER, 0);
	
	$messages = array();
	$messages['messages'] = [];
	for($i = 0;$i<$cout;$i++){
		$value = [];
		@$value['text'] = @'Mã học phần : '.$congno[$i][1].'-'.$congno[$i][2].'\nMôn Thi : '.$congno[$i][4].'\nNhóm : '.$congno[$i][6].'\nTừ sĩ số : '.$congno[$i][8].'\nNgày thi : '.$congno[$i][10].'-'.$congno[$i][11].'\nTiết Thi : '.$congno[$i][13].'->'.$congno[$i][14].'->'.$congno[$i][15].'\nPhòng Thi : '.$congno[$i][17].'\nLoại Thi : '.$congno[$i][19].'\nGhi Chú : '.$congno[$i][21];
		$messages['messages'][] = $value;
	}
	$a = json_encode($messages,JSON_UNESCAPED_UNICODE);
	$b  = preg_replace('@\\\\n@','n',$a);
	echo $b;
	// further processing ....
	
}
?>
