// const mysql = require('mysql');
const moment = require("moment");
// var mysqlConfig = require('../database/mysql');

// Connect to mySQL
// var con = mysql.createConnection(mysqlConfig);

// con.connect(function(err) {
//   if (err) throw err;
//   console.log("Connected to database!!!")
// });

module.exports = {
  // Auth token
  authenticateSocket: async function (socket, next) {
    if (socket.handshake.query.access_token) {
      var now = moment().format("YYYY-MM-DD HH:mm:ss");
      var tokenId = JSON.parse(
        Buffer.from(
          socket.handshake.query.access_token.split(".")[1],
          "base64"
        ).toString()
      ).jti;
      var sql = "SELECT expires_at FROM oauth_access_tokens WHERE id LIKE ?";
      // con.query(sql, tokenId, function (err, res) {
      //   var expires_at = moment(res[0].expires_at).format(
      //     "YYYY-MM-DD HH:mm:ss"
      //   );
      //   if (err) next(new Error("Authentication error"));
      //   if (res && res.length > 0 && expires_at >= now) {
      //     next();
      //   } else {
      //     next(new Error("Authentication error"));
      //   }
      // });
    } else {
      next(new Error("Authentication error"));
    }
  },
};
