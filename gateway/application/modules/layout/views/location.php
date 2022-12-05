<script type="text/javascript">
function success(position) {
  location = {
     'latitude': position.coords.latitude,
     'longitude': position.coords.longitude
   };
   console.log(location);
}

function error() {
  alert('Sorry, no position available.');
}

const options = {
  enableHighAccuracy: true,
  maximumAge: 30000,
  timeout: 27000
};
</script>
