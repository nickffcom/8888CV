export const SOCKET_KIND = {
  chat: 0,
  message: 1,
  preview: 2,
  nextBackImage: 3,
};

export const socketChatType = {
  createConversation: 0,
  updateConversation: 1,
  removeFileConversation: 2,
  removeMessageConversation: 3,
  previewImage: 4,
  nextBackImage: 5,
};

export const ROOM_CHAT_PREFIX = "chatRoom";
export const ROOM_MESSAGE_PREFIX = "messageRoom";
