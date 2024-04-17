import axios from "../../utils/axios";

export const getListChat = (data) => {
  return axios.get(`room/list`, { sender_id: data });
};

export const getDetailChat = (room_id) => {
  return axios.get(`room/${room_id}/detail`);
};

export const getAllListChat = (meeting_id, data) => {
  return axios.get(`/meeting/${meeting_id}/conversation/all`, { params: data });
};

export const getRoomID = (data) => {
  return axios.get(`room/id`, { params: data });
};

export const getCountUnread = (data) => {
  return axios.get(`room/unread/count`, { params: data });
};

export const seenedMessages = (senderID, data) => {
  return axios.get(`room/${senderID}/seen`, { params: data });
};

export const updateChat = (dataID, data) => {
  return axios.post(`/conversation/${dataID}/update`, { message: data });
};

export const createChat = (params) => {
  return axios.post(`/conversation/create`, params);
};
export const deleteChat = (conversation_id) => {
  return axios.post(`/conversation/${conversation_id}/delete`);
};
