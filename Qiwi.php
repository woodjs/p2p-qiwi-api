<?php
class Qiwi
{
  function __construct($public_key, $secret_key)
  {
    $this->public_key = $public_key;
    $this->secret_key = $secret_key;
  }

  function getInfo()
  {
    echo "Публичный ключ: $this->public_key ; Секретный ключ: $this->secret_key <br>";
  }

  function createBillForm($params)
  {
    $url = 'https://oplata.qiwi.com/create?publicKey=' . $this->public_key . '&amount=' . $params['amount'] . '&billId=' . $params['billId'] . '&successUrl=' . $params['successUrl'] . '&lifetime=' . $params['lifetime'] . '&customFields[themeCode]=' . $params['customFields'] . '&paySource=qw';
    return $url;
  }

  function checkBill($bill)
  {
    $url = 'https://api.qiwi.com/partner/bill/v1/bills/' . $bill . '';
    $ch = curl_init();
    $headers = array();
    $headers[] = "Accept: application/json";
    $headers[] = "Authorization: Bearer $this->secret_key";

    curl_setopt($ch, CURLOPT_URL, "$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }

  function lifetime()
  {
    $end_date = strtotime('+1 hours');
    $hour = date('H', $end_date);
    $minutes = date('i', $end_date);
    $date = date('Y-m-d', $end_date);
    $result =  $date . "T$hour$minutes";

    return $result;
  }


  function rejectBill()
  {
    $url = 'https://api.qiwi.com/partner/bill/v1/bills/bill8/reject';
    $ch = curl_init();
    $headers = array();
    $headers[] = "Accept: application/json";
    $headers[] = "Content-Type: application/json";
    $headers[] = "Authorization: Bearer $this->secret_key";
    curl_setopt_array($ch, array(
      CURLOPT_URL => "$url",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query(array())
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    echo "Ответ на Ваш запрос: " . $response;
  }
}
