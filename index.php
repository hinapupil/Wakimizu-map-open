<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>湧き水 Map</title>
  <style>
    #target {
      width: 100%;
      height: 90vh;
    }

    form {
      margin: 10px;
    }
  </style>
</head>

<body>

<?php
  // 入力された地名を変数にセットする
  if (isset($_GET['place'])) {
    $q = $_GET['place'];
  } else {
    $q = "千葉";
    $_GET['place'] = "千葉";
  }
  ?>

  <form method="GET" action="index.php">
    <label>都道府県を選択してください:</label>
    <select name="place">
      <option value="北海道">北海道</option>
      <option value="青森県">青森県</option>
      <option value="岩手県">岩手県</option>
      <option value="宮城県">宮城県</option>
      <option value="秋田県">秋田県</option>
      <option value="山形県">山形県</option>
      <option value="福島県">福島県</option>
      <option value="茨城県">茨城県</option>
      <option value="栃木県">栃木県</option>
      <option value="群馬県">群馬県</option>
      <option value="埼玉県">埼玉県</option>
      <option value="千葉県" selected>千葉県</option>
      <option value="東京都">東京都</option>
      <option value="神奈川県">神奈川県</option>
      <option value="新潟県">新潟県</option>
      <option value="富山県">富山県</option>
      <option value="石川県">石川県</option>
      <option value="福井県">福井県</option>
      <option value="山梨県">山梨県</option>
      <option value="長野県">長野県</option>
      <option value="岐阜県">岐阜県</option>
      <option value="静岡県">静岡県</option>
      <option value="愛知県">愛知県</option>
      <option value="三重県">三重県</option>
      <option value="滋賀県">滋賀県</option>
      <option value="京都府">京都府</option>
      <option value="大阪府">大阪府</option>
      <option value="兵庫県">兵庫県</option>
      <option value="奈良県">奈良県</option>
      <option value="和歌山県">和歌山県</option>
      <option value="鳥取県">鳥取県</option>
      <option value="島根県">島根県</option>
      <option value="岡山県">岡山県</option>
      <option value="広島県">広島県</option>
      <option value="山口県">山口県</option>
      <option value="徳島県">徳島県</option>
      <option value="香川県">香川県</option>
      <option value="愛媛県">愛媛県</option>
      <option value="高知県">高知県</option>
      <option value="福岡県">福岡県</option>
      <option value="佐賀県">佐賀県</option>
      <option value="長崎県">長崎県</option>
      <option value="熊本県">熊本県</option>
      <option value="大分県">大分県</option>
      <option value="宮崎県">宮崎県</option>
      <option value="鹿児島県">鹿児島県</option>
      <option value="沖縄県">沖縄県</option>
    </select>
    <input type="submit" value="検索する">
  </form>

  <div id="target"></div>

  <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=YOUR_API_KEY&callback=initMap" async defer></script>

  <?php
  //湧き水API用リクエストURLを生成する
  $req = "https://livlog.xyz/springwater/springWater?q=" . $q;
  //湧き水APIを用いてJSONデータをダウンロードする
  $json = file_get_contents($req);
  $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  $arr = json_decode($json, true);

  if ($arr === NULL) {
    return;
  } else {
    $json_count = count($arr["data"]);
    $bc_access = array();
    $bc_activity = array();
    $bc_address = array();
    $bc_createCd = array();
    $bc_createDate = array();
    $bc_furigana = array();
    $bc_id = array();
    $bc_latitude = array();
    $bc_longitude = array();
    $bc_name = array();
    $bc_oldAddress = array();
    $bc_overview = array();
    $bc_updateCd = array();
    $bc_updateDate = array();
    for ($i = $json_count - 1; $i >= 0; $i--) {
      $bc_access[] = $arr["data"][$i]["access"];
      $bc_activity[] = $arr["data"][$i]["activity"];
      $bc_address[] = $arr["data"][$i]["address"];
      $bc_createCd[] = $arr["data"][$i]["createCd"];
      $bc_createDate[] = $arr["data"][$i]["createDate"];
      $bc_furigana[] = $arr["data"][$i]["furigana"];
      $bc_id[] = $arr["data"][$i]["id"];
      $bc_latitude[] = $arr["data"][$i]["latitude"];
      $bc_longitude[] = $arr["data"][$i]["longitude"];
      $bc_name[] = $arr["data"][$i]["name"];
      $bc_oldAddress[] = $arr["data"][$i]["oldAddress"];
      $bc_overview[] = $arr["data"][$i]["overview"];
      $bc_updateCd[] = $arr["data"][$i]["updateCd"];
      $bc_updateDate[] = $arr["data"][$i]["updateDate"];
    }
  }
  ?>

  <script>
    function initMap() {

      var target = document.getElementById('target');
      var map;
      var marker = [];
      var infoWindow = [];

      var latitude = [];
      latitude = <?php echo json_encode($bc_latitude); ?>;
      var longitude = [];
      longitude = <?php echo json_encode($bc_longitude); ?>;
      var name = [];
      name = <?php echo json_encode($bc_name); ?>;
      var furigana = [];
      furigana = <?php echo json_encode($bc_furigana); ?>;
      var overview = [];
      overview = <?php echo json_encode($bc_overview); ?>;
      var address = [];
      address = <?php echo json_encode($bc_address); ?>;
      var access = [];
      access = <?php echo json_encode($bc_access); ?>;

      var j = <?php echo json_encode($json_count); ?>;

      var contentString = [];

      var place = [];
      for (var i = 0; i <= j; i++) {
        place[i] = {
          lat: latitude[i],
          lng: longitude[i]
        };
      };

      map = new google.maps.Map(target, {
        center: place[0],
        zoom: 10
      });

      for (var i = 0; i <= j; i++) {
        marker[i] = new google.maps.Marker({
          position: place[i],
          map: map,
          animation: google.maps.Animation.DROP
        });
        infoWindow[i] = new google.maps.InfoWindow({
          content: contentString[i] =
            "<h1><ruby><rb>" + name[i] + "</rb><rp>（</rp><rt>" + furigana[i] + "</rt><rp>）</rp></ruby></h1>" +
            "<address>住所: " + address[i] + "<br>緯度: " + latitude[i] + "<br>経度: " + longitude[i] + "</address>" +
            "<p>" + overview[i] + "</p>" +
            "<p>アクセス度: " + access[i] + "<p>"
        });
        markerEvent(i);
      }

      var currentInfoWindow
      
      function markerEvent(i) {
        marker[i].addListener('click', function() {
          //先に開いた情報ウィンドウがあれば、closeする
          if (currentInfoWindow) {currentInfoWindow.close();}
          //情報ウィンドウを開く
          infoWindow[i].open(map, marker[i]);
          //開いた情報ウィンドウを記録しておく
          currentInfoWindow = infoWindow[i];
        });
      }

    }
  </script>

</body>

</html>