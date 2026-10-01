Pusher.logToConsole = true;
const PUSHER_APP_ID = document.querySelector("meta[name=P-A-ID]").getAttribute('content');
const PUSHER_CLUSTER = document.querySelector("meta[name=P-CLUSTER]").getAttribute('content');
const BASE_URL = document.querySelector("meta[name=APP-DOMAIN]").getAttribute('content');
const AUTH_END_POINT = `${BASE_URL}/pusher/auth/:socketId/:channelName`;

const makeAuthEndPointForPusher = (socketId, channelName) => {
    return AUTH_END_POINT
        .replace(':socketId', encodeURIComponent(socketId))
        .replace(':channelName', encodeURIComponent(channelName));
};

var pusher = new Pusher(PUSHER_APP_ID, {
    cluster: PUSHER_CLUSTER,
    authorizer: (channel) => {
        return {
            authorize: (socketId, callback) => {
                fetch(makeAuthEndPointForPusher(socketId, channel.name), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(async (response) => {
                        const data = await response.json();

                        if (!response.ok) {
                            throw data;
                        }

                        callback(null, data);
                    })
                    .catch((error) => callback(error, null));
            },
        };
    },
});
