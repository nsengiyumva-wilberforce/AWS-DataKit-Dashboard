<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Locations</h1>
  <div class="btn-toolbar mb-md-0">
    <form class="form-inline mr-2" id="form-row-data-report" method="POST">
      <label class="my-1 mr-2">Year</label>
      <select name="year" id="year" class="custom-select my-1 mr-sm-2">
        <option value="2024">2024</option>
        <option value="2023">2023</option>
        <option value="2022">2022</option>
        <option value="2021">2021</option>
        <option value="2020">2020</option>
        <option value="2019">2019</option>
      </select>
      <input type="hidden" name="form_id" value="<?= $form_id ?>">
    </form>
  </div>
</div>
<div class="row mb-3">
  <div class="col">
    <?= $report_title ?>
  </div>
</div>
<input type="hidden" class="form_id" value="<?= $form_id ?>" />
<div class="row mb-5">
  <div class="col">
    <div id="map" style="height: 700px;">
      <p style="text-align: center; margin: 20px;">Oops!!!<br>No map data found<br>

        <a href="<?= base_url('maps') ?>">Return to Map List</a>
      </p>
    </div>
  </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
  function initMap() {
    window.initMap = initMap;
    var form_id = $(".form_id").val();
    var year = $("#year").val();
    loadDynamicMap(form_id, year);
  }
  //check if the doecument has finished loading
  function loadDynamicMap(form_id, year) {
    //alert("heheheh");
    var map_url = <?php echo json_encode($map_url); ?>+"entry-geodata?form_id=" + form_id + "&year=" + year;
    const myLatLng = { lat: 0.3476, lng: 32.5825 };
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 7,
      center: myLatLng,
    });
    $.ajax({
      method: "get",
      url: map_url,
      dataType: "json",
      success: function (data) {
        var json = data.data;
        //alert(JSON.stringify(json));
        $.each(json, function (key, val) {

          //alert(val.coordinates.lat);
          var latitude = parseFloat(val.coordinates.lat);
          var longitude = parseFloat(val.coordinates.lon);

          var sub_title = val.sub_title;
          var name = val.title;
          var latlng = { lat: latitude, lng: longitude };


          var marker = new google.maps.Marker({
            position: latlng,
            map,
            //label: name,
            title: "Name:"+name+"\n"+"Village:"+sub_title
          });


          var contentString = "<div style='width:300px;height:200px;'>" + name + "<br/> <br/></div>";
          var infowindow = new google.maps.InfoWindow({
            content: contentString,
          });



          marker.addListener("click", () => {
            infowindow.open({
              anchor: marker,
              map,
              shouldFocus: false,
            });
          });

        });

      }
    });
  }

  //on select change for year
  $('#year').change(function () {
    //fetch the data for the selected year and update the map
    var form_id = $(".form_id").val();
    var year = $(this).val();
    loadDynamicMap(form_id, year);

  })

</script>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8r6EQIEBfDtlFHPfFjqB-8dlWgPJcZ5Q&callback=initMap&v=weekly"
  defer></script>