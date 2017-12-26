<?php
header("Content-Type: text/html; charset=utf-8");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://kyfw.12306.cn/otn/resources/js/framework/station_name.js?station_version=1.9035");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$cityText = curl_exec($ch);
curl_close($ch);

preg_match_all('/([\x{4e00}-\x{9fa5}]+)\|([A-Z]+)/u', $cityText, $match);
$citysMap = array();

foreach ($match[0] as $city) {
  $cityMap = explode('|', $city);
  $citysMap[$cityMap[0]] = $cityMap[1];
}
var_dump($_GET);
$from = $_GET['from'];
$to = $_GET['to'];
$date = $_GET['date'];

echo json_encode($from);

if (array_key_exists($from, $citysMap) && array_key_exists($to, $citysMap)) {
  getData();
} else {
  echo '输入的地址不正确';
}


function getData()
{
  global $date, $from, $to, $citysMap;

  print_r($citysMap);

  $url = "https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date={$date}&leftTicketDTO.from_station={$citysMap[$from]}&leftTicketDTO.to_station={$citysMap[$to]}&purpose_codes=ADULT";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $data = json_decode(curl_exec($ch))->data->result;
  curl_close($ch);

  handleData($data);
//  print_r($data);
}

function handleData($data)
{
  $dataArray = array();
  foreach ($data as $item) {
    $itemArray = explode('|', $item);

    $data = array();

    $data['train_no'] = $itemArray[2];
    $data['station_train_code'] = $itemArray[3];
    $data['start_station_telecode'] = $itemArray[4];
    $data['end_station_telecode'] = $itemArray[5];
    $data['from_station_telecode'] = $itemArray[6];
    $data['to_station_telecode'] = $itemArray[7];
    $data['start_time'] = $itemArray[8];
    $data['arrive_time'] = $itemArray[9];
    $data['lishi'] = $itemArray[10];
    $data['canWebBuy'] = $itemArray[11];
    $data['yp_info'] = $itemArray[12];
    $data['start_train_date'] = $itemArray[13];
    $data['train_seat_feature'] = $itemArray[14];
    $data['location_code'] = $itemArray[15];
    $data['from_station_no'] = $itemArray[16];
    $data['to_station_no'] = $itemArray[17];
    $data['is_support_card'] = $itemArray[18];
    $data['controlled_train_flag'] = $itemArray[19];
    $data['gg_num'] = $itemArray[20] ? $itemArray[20] : '--';
    $data['gr_num'] = $itemArray[21] ? $itemArray[21] : '--';
    $data['qt_num'] = $itemArray[22] ? $itemArray[22] : '--';
    $data['rw_num'] = $itemArray[23] ? $itemArray[23] : '--';
    $data['rz_num'] = $itemArray[24] ? $itemArray[24] : '--';
    $data['tz_num'] = $itemArray[25] ? $itemArray[25] : '--';
    $data['wz_num'] = $itemArray[26] ? $itemArray[26] : '--';
    $data['yb_num'] = $itemArray[27] ? $itemArray[27] : '--';
    $data['yw_num'] = $itemArray[28] ? $itemArray[28] : '--';
    $data['yz_num'] = $itemArray[29] ? $itemArray[29] : '--';
    $data['ze_num'] = $itemArray[30] ? $itemArray[30] : '--';
    $data['zy_num'] = $itemArray[31] ? $itemArray[31] : '--';
    $data['swz_num'] = $itemArray[32] ? $itemArray[32] : '--';
    $data['srrb_num'] = $itemArray[33] ? $itemArray[33] : '--';
    $data['yp_ex'] = $itemArray[34];
    $data['seat_types'] = $itemArray[35];

    array_push($dataArray, $data);
  }

  print_r($dataArray);
}
