Pusher.logToConsole = true;
const PUSHER_APP_ID = document.querySelector("meta[name=P-A-ID]").getAttribute('content');
const PUSHER_CLUSTER = document.querySelector("meta[name=P-CLUSTER]").getAttribute('content');
const BASE_URL = document.querySelector("meta[name=APP-DOMAIN]").getAttribute('content');
const AUTH_END_POINT = `${BASE_URL}/pusher/auth/:socketId/:channelName`;

var pusher = new Pusher(PUSHER_APP_ID, {
    cluster: PUSHER_CLUSTER,
});

makeAuthEndPointForPusher = (socketId, channelName) => {
    var endpoint = AUTH_END_POINT.replace(':socketId', socketId).replace(":channelName", channelName)
    return endpoint;
}