<?php
header('Content-Type: application/json; charset=utf-8');
$mssv= $_GET['mssv'];
$type= $_GET['type'];
@$ngaythu = $_GET['ngaythu'];
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
		@$value['text'] = @'=========\n'.'Mã học phần : '.$congno[$i][1].'\nNội dung thu : '.$congno[$i][2].'\nTín chỉ : '.$congno[$i][3].'\nKhấu trừ : '.$congno[$i][4].'\nCông nợ : '.$congno[$i][5].'\n=========';
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
				'__EVENTTARGET=&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE=%2FwEPDwUKMTE0NzM4Mzc3Nw9kFgJmD2QWAgIBD2QWBgIBD2QWBGYPEGRkFgECAWQCAQ8PFgIeB1Zpc2libGVoZGQCBQ8QZA8WG2YCAQICAgMCBAIFAgYCBwIIAgkCCgILAgwCDQIOAg8CEAIRAhICEwIUAhUCFgIXAhgCGQIaFhsQBQpU4bqldCBj4bqjBQItMWcQBQRLaG9hBQM0MDdnEAUPLS0tS2hvYSBExrDhu6NjBQM0MDhnEAUOR2nhu5tpIHRoaeG7h3UFAzM3N2cQBRdRdXkgY2jhur8gLSBRdXkgxJHhu4tuaAUDMzQ1ZxAFDC0tLVF1eSBjaOG6vwUDMzQ2ZxAFDi0tLVF1eSDEkeG7i25oBQMzNjRnEAUeLS0tQ2jGsMahbmcgdHLDrG5oIMSRw6BvIHThuqFvBQMzNDhnEAUULS0tQ2h14bqpbiDEkeG6p3UgcmEFAzM1MWcQBRhL4bq%2FIGhv4bqhY2ggxJHDoG8gdOG6oW8FAzM2NWcQBQ1HaeG6o25nIHZpw6puBQMzNjdnEAUJLS0tVXBkYXRlBQM0MDlnEAUKU2luaCB2acOqbgUDMzU3ZxAFFi0tLVPhu5UgdGF5IFNpbmggVmnDqm4FAzM3NmcQBQtUaMO0bmcgYsOhbwUDMzY4ZxAFDEJp4buDdSBt4bqrdQUDMzY5ZxAFDFtD4bqpbSBuYW5nXQUDMzc4ZxAFC1Row7RuZyBiw6FvBQMzNjhnEAUhVGjDtG5nIGLDoW8gZMOgbmggY2hvIEtow7NhIG3hu5tpBQM0MTRnEAUvVi92IMSQxINuZyBrw70gSOG7jWMgcGjhuqduLCBUS0IgdsOgIEjhu41jIHBow60FAzQwNGcQBRNWL3YgUXXhuqNuIGzDvSBIU1NWBQM0MTNnEAUSVi92IFThu5F0IG5naGnhu4dwBQM0MDNnEAUdVi92IFR1eeG7g24gc2luaCBMacOqbiB0aMO0bmcFAzQwNmcQBR5WL3YgVGjhu7FjIHThuq1wIC0gVmnhu4djIGzDoG0FAzQwNWcQBQlRdXkgY2jhur8FAzM0NmcQBQtRdXkgxJHhu4tuaAUDMzY0ZxAFEUNodeG6qW4gxJHhuqd1IHJhBQMzNTFnZGQCBw9kFgQCAw9kFgZmD2QWBAIDDxAPFgYeDURhdGFUZXh0RmllbGQFCFRlbkRvblZpHg5EYXRhVmFsdWVGaWVsZAUHSUREb25WaR4LXyFEYXRhQm91bmRnZBAVEApDxqEgc%2BG7nyAxCkPGoSBz4bufIDIKQ8ahIHPhu58gMwpDxqEgc%2BG7nyA0CkPGoSBz4bufIDUKQ8ahIHPhu58gNhdMacOqbiBr4bq%2FdCDEkOG7k25nIE5haRlMacOqbiBr4bq%2FdCDEkOG7k25nIFRow6FwGUxpw6puIGvhur90IELDrG5oIETGsMahbmcWTGnDqm4ga%2BG6v3QgxJDEg2sgTMSDaxRMacOqbiBr4bq%2FdCBBbiBHaWFuZxNMacOqbiBr4bq%2FdCBMb25nIEFuFUxpw6puIGvhur90IFPDoGkgR8OybhdMacOqbiBr4bq%2FdCBUw6J5IE5hbSDDgRZU4bqtcCDEkW%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%3D%3D&ctl00%24ucPhieuKhaoSat1%24RadioButtonList1=0&ctl00%24DdListMenu=-1&ctl00%24ContentPlaceHolder%24SearchType=radSinhVien&ctl00%24ContentPlaceHolder%24txtMSSV='.$mssv.'&ctl00%24ContentPlaceHolder%24cboHocKy3=36&ctl00%24ContentPlaceHolder%24TestType=radAllTest&ctl00%24ContentPlaceHolder%24btnSearch=Xem+l%E1%BB%8Bch+thi&ctl00%24ucRight1%24txtMaSV=&ctl00%24ucRight1%24txtMatKhau=&ctl00%24ucRight1%24rdSinhVien=1&txtSecurityCodeValue=c7c4f08c18fb6dfbebd632e12fab7628&ctl00%24ucRight1%24txtEncodeMatKhau=');
				
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

}
if ($type== "xemlichtheotuan")
{	//require('src/Html2Text.php');
	$web1='http://phongdaotao2.ntt.edu.vn/LichHocLichThiTuan.aspx?MSSV='.$mssv;
	$url1 = file_get_contents($web1);
	$cacngaytrongtuan_buoisang = '@<td style="vertical-align: top; width: 130px;">
                        (.*?)
                    </td>@';
	$cacngaytrongtuan_chieu_toi= '@<td style="vertical-align: top">
                        (.*?)
                    </td>@';
	//tên môn học
	$ten_mh = '@</br><span class="span-display">(.*?)</span></span><hr/>@';
	//tiết học
	$tiet_hoc = '@<span class="span-label">Tiết:</span><span class="span-display"> (.*?)</span>@';
	//giảng viên
	$giang_vien = '@<span class="span-label">GV:</span><span class="span-display"> (.*?)</span>@';
	//phòng học
	$phong_hoc = '@<span class="span-label">Phòng:</span><span class="span-display"> (.*?)</span>@';
	//ghi chú 
	$ghi_chu = '@<span class="span-label">Ghi chú:</span><span class="span-display">(.*?)</span>@';
	//từ sĩ số
	$tu_si_so = '@<span class="span-label">Từ sĩ số:</span><span class="span-display">(.*?)</span>@';
	//nhóm
	$nhom = '@<span class="span-label">Nhóm:</span><span class="span-display">(.*?)</span>@';
	
	//phân chia môn học trong 1 buổi, 1 buổi có thể học tới 2 môn học.
	$chia_mon = '@<div class="div-\w+">(.*?)</div>@';
	// sau khi lấy được chia môn thì check if của tất cả các trường trên ( từ môn học -> nhóm )		
	$cout = preg_match_all($cacngaytrongtuan_buoisang, $url1, $buoisang, PREG_SET_ORDER, 0);
	// sau khi lấy được chia môn thì check if của tất cả các trường trên ( từ môn học -> nhóm )	 buổi chiều
	preg_match_all($cacngaytrongtuan_chieu_toi, $url1, $buoichieu_toi, PREG_SET_ORDER, 0); //=>14 cái
		preg_match_all($chia_mon, $buoisang[0][1], $thuhai_buoisang, PREG_SET_ORDER, 0);//sáng
		//====môn buổi sáng=====//
		$mon1_buoisang_2 = @$thuhai_buoisang[0][1];
		$mon2_buoisang_2 = @$thuhai_buoisang[0][2];
		preg_match_all($chia_mon, $buoichieu_toi[0][1], $thuhai_buoichieu, PREG_SET_ORDER, 0);//chiều
		//====môn buổi chiều=====//
		$mon1_buoichieu_2 = @$thuhai_buoichieu[0][1];
		$mon2_buoichieu_2 = @$thuhai_buoichieu[0][2];
		preg_match_all($chia_mon, $buoichieu_toi[0][8], $thuhai_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_2 = @$thuhai_buoitoi[0][1];
		$mon2_buoitoi_2 = @$thuhai_buoitoi[0][2];
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_2, $thuhai_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_2, $thuhai_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_2, $thuhai_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_2, $thuhai_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_2, $thuhai_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_2, $thuhai_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang_2, $thuhai_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_2, $thuhai_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_2, $thuhai_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_2, $thuhai_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_2, $thuhai_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_2, $thuhai_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_2, $thuhai_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_2, $thuhai_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_2, $thuhai_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_2, $thuhai_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_2, $thuhai_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_2, $thuhai_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_2, $thuhai_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_2, $thuhai_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_2, $thuhai_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoichieu_2, $thuhai_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoichieu_2, $thuhai_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoichieu_2, $thuhai_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoichieu_2, $thuhai_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoichieu_2, $thuhai_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoichieu_2, $thuhai_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoichieu_2, $thuhai_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_2, $thuhai_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_2, $thuhai_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_2, $thuhai_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_2, $thuhai_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_2, $thuhai_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_2, $thuhai_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_2, $thuhai_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_2, $thuhai_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_2, $thuhai_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_2, $thuhai_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_2, $thuhai_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_2, $thuhai_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_2, $thuhai_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_2, $thuhai_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		
		
		//echo $buoisang[1][1];
		preg_match_all($chia_mon, $buoisang[1][1], $thuba_buoisang, PREG_SET_ORDER, 0);
		//====môn buổi sáng=====//
		$mon1_buoisang_3 = @$thuba_buoisang[0][1];
		$mon2_buoisang_3 = @$thuba_buoisang[1][0];
		preg_match_all($chia_mon, $buoichieu_toi[0][2], $thuba_buoichieu, PREG_SET_ORDER, 0);//chiều
		//====môn buổi chiều=====//
		$mon1_buoichieu_3 = @$thuba_buoichieu[0][3];
		$mon2_buoichieu_3 = @$thuba_buoichieu[0][4];
		preg_match_all($chia_mon, $buoichieu_toi[0][9], $thuba_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_3 = @$thuba_buoitoi[0][5];
		$mon2_buoitoi_3 = @$thuba_buoitoi[0][6];
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_3, $thu3_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_3, $thu3_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_3, $thu3_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_3, $thu3_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_3, $thu3_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_3, $thu3_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang, $thu3_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_3, $thu3_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_3, $thu3_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_3, $thu3_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_3, $thu3_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_3, $thu3_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_3, $thu3_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_3, $thu3_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_3, $thu3_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_3, $thu3_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_3, $thu3_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_3, $thu3_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_3, $thu3_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_3, $thu3_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_3, $thu3_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoichieu_3, $thu3_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoichieu_3, $thu3_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoichieu_3, $thu3_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoichieu_3, $thu3_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoichieu_3, $thu3_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoichieu_3, $thu3_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoichieu_3, $thu3_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_3, $thu3_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_3, $thu3_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_3, $thu3_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_3, $thu3_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_3, $thu3_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_3, $thu3_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_3, $thu3_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_3, $thu3_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_3, $thu3_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_3, $thu3_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_3, $thu3_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_3, $thu3_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_3, $thu3_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_3, $thu3_buoisang_mon2_nhom, PREG_SET_ORDER, 0);

		//echo $buoisang[2][1];
		@preg_match_all($chia_mon, $buoisang[2][1], $thu4_buoisang, PREG_SET_ORDER, 0);
		//====môn buổi sáng=====//
		$mon1_buoisang_4 = @$thu4_buoisang[0][1];
		$mon2_buoisang_4 = @$thu4_buoisang[0][2];
		//====môn buổi chiều=====//
		@preg_match_all($chia_mon, $buoichieu_toi[0][3], $thu4_buoichieu, PREG_SET_ORDER, 0);//chiều
		$mon1_buoichieu_4 = @$thu4_buoichieu[0][3];
		$mon2_buoichieu_4 = @$thu4_buoichieu[0][4];
		@preg_match_all($chia_mon, $buoichieu_toi[0][10], $thu4_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_4 = @$thu4_buoitoi[0][5];
		$mon2_buoitoi_4 = @$thu4_buoitoi[0][6];
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_4, $thu4_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_4, $thu4_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_4, $thu4_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_4, $thu4_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_4, $thu4_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_4, $thu4_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang_4, $thu4_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_4, $thu4_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_4, $thu4_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_4, $thu4_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_4, $thu4_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_4, $thu4_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_4, $thu4_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_4, $thu4_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================	
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_4, $thu4_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_4, $thu4_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_4, $thu4_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_4, $thu4_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_4, $thu4_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_4, $thu4_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_4, $thu4_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoichieu_4, $thu4_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoichieu_4, $thu4_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoichieu_4, $thu4_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoichieu_4, $thu4_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoichieu_4, $thu4_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoichieu_4, $thu4_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoichieu_4, $thu4_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_4, $thu4_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_4, $thu4_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_4, $thu4_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_4, $thu4_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_4, $thu4_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_4, $thu4_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_4, $thu4_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_4, $thu4_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_4, $thu4_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_4, $thu4_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_4, $thu4_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_4, $thu4_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_4, $thu4_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_4, $thu4_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		
		//echo $buoisang[3][1];
		$cout = preg_match_all($chia_mon, $buoisang[3][1], $thu5_buoisang, PREG_SET_ORDER, 0);// sáng
		//====môn buổi sáng=====//
		$mon1_buoisang_5 = @$thu5_buoisang[0][1];
		$mon2_buoisang_5 = @$thu5_buoisang[1][0];
		@preg_match_all($chia_mon, $buoichieu_toi[0][4], $thu5_buoichieu, PREG_SET_ORDER, 0);//chiều
		//====môn buổi chiều=====//
		$mon1_buoichieu_5 = @$thu5_buoichieu[0][1];
		$mon2_buoichieu_5 = @$thu5_buoichieu[1][0];
		@preg_match_all($chia_mon, $buoichieu_toi[0][11], $thu5_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_5 = @$thu5_buoitoi[0][1];
		$mon2_buoitoi_5 = @$thu5_buoitoi[0][2];
		//echo $mon2_buoisang;
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_5, $thu5_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_5, $thu5_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_5, $thu5_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_5, $thu5_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_5, $thu5_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_5, $thu5_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang_5, $thu5_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_5, $thu5_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_5, $thu5_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_5, $thu5_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_5, $thu5_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_5, $thu5_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_5, $thu5_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_5, $thu5_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_5, $thu5_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_5, $thu5_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_5, $thu5_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_5, $thu5_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_5, $thu5_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_5, $thu5_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_5, $thu5_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoichieu_5, $thu5_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoichieu_5, $thu5_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoichieu_5, $thu5_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoichieu_5, $thu5_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoichieu_5, $thu5_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoichieu_5, $thu5_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoichieu_5, $thu5_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_5, $thu5_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_5, $thu5_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_5, $thu5_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_5, $thu5_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_5, $thu5_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_5, $thu5_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_5, $thu5_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_5, $thu5_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_5, $thu5_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_5, $thu5_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_5, $thu5_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_5, $thu5_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_5, $thu5_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_5, $thu5_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		
		//echo $buoisang[4][1];
		preg_match_all($chia_mon, $buoisang[2][1], $thu6_buoisang, PREG_SET_ORDER, 0);
		//====môn buổi sáng=====//
		$mon1_buoisang_6 = @$thu6_buoisang[0][1];
		$mon2_buoisang_6 = @$thu6_buoisang[0][2];
		preg_match_all($chia_mon, $buoichieu_toi[0][5], $thu6_buoichieu, PREG_SET_ORDER, 0);//chiều
		//====môn buổi chiều=====//
		$mon1_buoichieu_6 = @$thu6_buoichieu[0][3];
		$mon2_buoichieu_6 = @$thu6_buoichieu[0][4];
		preg_match_all($chia_mon, $buoichieu_toi[0][12], $thu6_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_6 = @$thu6_buoitoi[0][5];
		$mon2_buoitoi_6 = @$thu6_buoitoi[0][6];
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_6, $thu6_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_6, $thu6_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_6, $thu6_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_6, $thu6_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_6, $thu6_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_6, $thu6_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang_6, $thu6_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_6, $thu6_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_6, $thu6_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_6, $thu6_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_6, $thu6_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_6, $thu6_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_6, $thu6_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_6, $thu6_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_6, $thu6_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_6, $thu6_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_6, $thu6_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_6, $thu6_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_6, $thu6_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_6, $thu6_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_6, $thu6_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoichieu_6, $thu6_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoichieu_6, $thu6_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoichieu_6, $thu6_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoichieu_6, $thu6_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoichieu_6, $thu6_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoichieu_6, $thu6_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoichieu_6, $thu6_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_6, $thu6_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_6, $thu6_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_6, $thu6_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_6, $thu6_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_6, $thu6_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_6, $thu6_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_6, $thu6_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_6, $thu6_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_6, $thu6_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_6, $thu6_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_6, $thu6_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_6, $thu6_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_6, $thu6_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_6, $thu6_buoisang_mon2_nhom, PREG_SET_ORDER, 0);

		//echo $buoisang[5][1];
		preg_match_all($chia_mon, $buoisang[5][1], $thu7_buoisang, PREG_SET_ORDER, 0);
		//====môn buổi sáng=====//
		$mon1_buoisang_7 = @$thu7_buoisang[0][1];
		$mon2_buoisang_7 = @$thu7_buoisang[0][2];
		preg_match_all($chia_mon, $buoichieu_toi[0][6], $thu7_buoichieu, PREG_SET_ORDER, 0);//chiều
		//====môn buổi chiều=====//
		$mon1_buoichieu_7 = @$thu7_buoichieu[0][3];
		$mon2_buoichieu_7 = @$thu7_buoichieu[0][4];
		preg_match_all($chia_mon, $buoichieu_toi[0][13], $thu7_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_7 = @$thu7_buoitoi[0][5];
		$mon2_buoitoi_7 = @$thu7_buoitoi[0][6];
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_7, $thu7_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_7, $thu7_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_7, $thu7_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_7, $thu7_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_7, $thu7_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_7, $thu7_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang_7, $thu7_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_7, $thu7_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_7, $thu7_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_7, $thu7_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_7, $thu7_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_7, $thu7_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_7, $thu7_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_7, $thu7_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_7, $thu7_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_7, $thu7_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_7, $thu7_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_7, $thu7_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_7, $thu7_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_7, $thu7_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_7, $thu7_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_7, $thu7_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_7, $thu7_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_7, $thu7_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_7, $thu7_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_7, $thu7_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_7, $thu7_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_7, $thu7_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_7, $thu7_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_7, $thu7_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_7, $thu7_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_7, $thu7_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_7, $thu7_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_7, $thu7_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_7, $thu7_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_7, $thu7_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_7, $thu7_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_7, $thu7_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_7, $thu7_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_7, $thu7_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_7, $thu7_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_7, $thu7_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		
		//echo $buoisang[6][1];
		preg_match_all($chia_mon, $buoisang[2][1], $chunhat_buoisang, PREG_SET_ORDER, 0);
		//====môn buổi sáng=====//
		$mon1_buoisang_cn = @$chunhat_buoisang[0][1];
		$mon2_buoisang_cn = @$chunhat_buoisang[0][2];
		preg_match_all($chia_mon, $buoichieu_toi[0][7], $chunhat_buoichieu, PREG_SET_ORDER, 0);//chiều
		//====môn buổi chiều=====//
		$mon1_buoichieu_cn = @$chunhat_buoichieu[0][3];
		$mon2_buoichieu_cn = @$chunhat_buoichieu[0][4];
		preg_match_all($chia_mon, $buoichieu_toi[0][14], $chunhat_buoitoi, PREG_SET_ORDER, 0);//tối
		//====môn buổi tối=====//
		$mon1_buoitoi_cn = @$chunhat_buoitoi[0][5];
		$mon2_buoitoi_cn = @$chunhat_buoitoi[0][6];
		//======================buổi sáng===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoisang_cn, $chunhat_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoisang_cn, $chunhat_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoisang_cn, $chunhat_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoisang_cn, $chunhat_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoisang_cn, $chunhat_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoisang_cn, $chunhat_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoisang_cn, $chunhat_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoisang_cn, $chunhat_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoisang_cn, $chunhat_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoisang_cn, $chunhat_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoisang_cn, $chunhat_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoisang_cn, $chunhat_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoisang_cn, $chunhat_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoisang_cn, $chunhat_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi chiều===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoichieu_cn, $chunhat_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoichieu_cn, $chunhat_buoisang_mon2_nhom, PREG_SET_ORDER, 0);
		//======================buổi tối===================================================
		//=================== môn 1========================================================//
		//tên môn học
		preg_match_all($ten_mh, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon1_buoitoi_cn, $chunhat_buoisang_mon1_nhom, PREG_SET_ORDER, 0);
		//=================== môn 2===================================================
		//tên môn học
		preg_match_all($ten_mh, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_tenmonhoc, PREG_SET_ORDER, 0);
		//tiết học
		preg_match_all($tiet_hoc, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_tiethoc, PREG_SET_ORDER, 0);
		//giảng viên
		preg_match_all($giang_vien, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_giangvien, PREG_SET_ORDER, 0);
		//phòng học
		preg_match_all($phong_hoc, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_phonghoc, PREG_SET_ORDER, 0);
		//ghi chú 
		preg_match_all($ghi_chu, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_ghichu, PREG_SET_ORDER, 0);
		//từ sĩ số
		preg_match_all($tu_si_so, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_tusiso, PREG_SET_ORDER, 0);
		//nhóm
		preg_match_all($nhom, $mon2_buoitoi_cn, $chunhat_buoisang_mon2_nhom, PREG_SET_ORDER, 0);	
		
		$arr1 = array (
		  'messages' => 
		  array (
			0 => 
			array (
			  'text' => 'Thứ 2',
			),
			1 => 
			array (
			  'text' => 'Thứ 3 ',
			),
			2 => 
			array (
			  'text' => 'Thứ 4',
			),
			3 => 
			array (
			  'text' => 'Thứ 5',
			),
			4 => 
			array (
			  'text' => 'Thứ 6',
			),
			5 => 
			array (
			  'text' => 'Thứ 7',
			),
			6 => 
			array (
			  'text' => 'Chủ Nhật ',
			),
		  ),
		);
}
?>