<script type="text/javascript">
  $(document).ready(function(){
    var vpanel = $("#varify-port");
      getLocation();
  });

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      console.log("Geolocation is not supported by this browser.");
    }
  }

  function showPosition(position) {
    console.log(position);
  }
</script>
