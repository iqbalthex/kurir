const getId = id => document.getElementById(id);
const qS = q => document.querySelector(q);

const setDestBtn = getId('set-dest-btn');
const destination = getId('destination');
const addItem = getId('addItem');

let map, infoWindow, directionsService, directionsRenderer;
let selectInfo;


window.onclick = function(e){
  try{
    if(e.target === setDestBtn){
      addItem.style.width = '0';
      qS('div.modal-backdrop').style.width = '0';
      selectInfo.classList.remove('d-none');
    } else {
      addItem.style.width = '100%';
      qS('div.modal-backdrop').style.width = '100%';
      selectInfo.classList.add('d-none');
    }
  } catch{}
}


window.initMap = function(){
  const center = { lat: -0.507068, lng: 101.447777 };
  map = new google.maps.Map(getId('map'), {
    center,
    zoom: 11,
    disableDefaultUI: true,
  });

  infoWindow = new google.maps.InfoWindow();
  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer();
  directionsRenderer.setMap(map);

  selectInfo = document.createElement('div');
  const selectInfoClassList = 'mt-2 bg-info fs-4 px-2 py-1 d-none';
  selectInfoClassList.split(' ').forEach(className => {
    selectInfo.classList.add(className);
  });
  selectInfo.style.transform = 'translateX(-50%)';
  selectInfo.innerHTML = 'Silahkan pilih tujuan pengiriman dengan mengklik peta';

  map.controls[google.maps.ControlPosition.TOP_CENTER].push(selectInfo);

  map.addListener('click', setDestination);
}


function setDestination(e){
  const lat = parseFloat(e.latLng.lat());
  const lng = parseFloat(e.latLng.lng());
  destination.value = `${lat}, ${lng}`;
}


function handleLocateError(browserHasGeolocation, pos){
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed.' :
    "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}


function calcRoute(destinationLat, destinationLng){
  const destination = { lat: destinationLat, lng: destinationLng };

  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(position => {
      const pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };

      map.setCenter(pos);

      const request = { origin: pos, destination, travelMode: 'DRIVING' };

      directionsService.route(request, function(result, status){
        if(status === 'OK'){
          // const { distance, duration, start_location, end_location } = result.routes[0].legs[0];
          // const paths = result[0].routes[0].overview_path;
          directionsRenderer.setDirections(result);
        }
      });
    }, () => {
      handleLocateError( true, map.getCenter() );
      console.log('error true');
    });
  } else {
    handleLocateError( false, map.getCenter() );
    console.log('error false');
  }
}

const items = document.querySelectorAll('div.dropdown.notif li[data-lat][data-lng]');
items.forEach(item => {
  item.addEventListener('click', () => {
    let { lat, lng } = item.dataset;
    lat = parseFloat(lat);
    lng = parseFloat(lng);

    calcRoute(lat, lng);
  });
});
