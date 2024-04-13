module.exports = {
  cors: {
    origins: "*:*",
    method: ["GET", "POST", "PUT", "PATCH"],
  },
  transports: ["websocket", "polling", "flashsocket"],
  reconnection: false,
  handlePreflightRequest: (req, res) => {
    res.writeHead(200, {
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET,POST",
      "Access-Control-Allow-Headers": "my-custom-header",
      "Access-Control-Allow-Credentials": true,
    });
    res.end();
  },
};
