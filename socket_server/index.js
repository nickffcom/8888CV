const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");
require("dotenv").config();
var cors = require("./config/cors");
const socketKind = require("./config/const").SOCKET_KIND;
const socketChatType = require("./config/const").SOCKET_CHAT_TYPE;

//Here you slove  Cors problem!
const io = new Server(server, cors);

/**
 * Init port of socket server
 */
server.listen(process.env.SOCKET_SERVER_PORT, () => {
  console.log("sever start", process.env.SOCKET_SERVER_PORT);
});

const getClientRoom = () => {
  let index = 0;
  while (true) {
    if (
      !io.sockets.adapter.rooms[index] ||
      io.sockets.adapter.rooms[index].length < 2
    ) {
      return index;
    }
    index++;
  }
};
io.on("connection", (socket) => {
  /**
   * Receive room from client -> create room in socket
   */
  socket.on("joinRoom", function (data) {
    const clientRoom = getClientRoom();
    socket.join(clientRoom);
    if (data.kind == socketKind.chat) {
      socket.chatRoom = clientRoom;
    } else if (data.kind == socketKind.message) {
      socket.messageRoom = clientRoom;
    } else if (data.kind == socketKind.preview) {
      socket.previewRoom = clientRoom;
    } else if (data.kind == socketKind.nextBackImage) {
      socket.eventImage = clientRoom;
    }
  });
  /**
   * Listen signal from client
   */
  socket.on("sendSignal", function (data) {
    if (data.kind == socketKind.chat) {
      io.to(socket.chatRoom).emit("signal", data);
      //   if (data.type == socketChatType.createConversation) {
      //     io.to(data.receiver_id).emit("countUnread", data);
      //   }
      // } else if (data.kind == socketKind.message) {
      //   io.to(socket.messageRoom).emit("signal", data);
      //   if (data.message_receiver_id) {
      //     io.to(data.message_receiver_id).emit("hasNewMessage", data);
      //   }
    } else if (data.kind == socketKind.preview) {
      io.to(socket.previewRoom).emit("previewImage", data);
    } else if (data.kind == socketKind.nextBackImage) {
      io.to(socket.eventImage).emit("eventImage", data);
    }
  });
  /**
   * Receive room user socket leave from client
   */
  socket.on("leaveRoom", function (data) {
    socket.leave(data.room);
  });
});
