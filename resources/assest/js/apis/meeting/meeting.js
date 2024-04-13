import axios from "../../utils/axios";

export const getListUser = () => {
  return axios.get(`user/list`);
};

export const getListMeeting = (data) => {
  return axios.get(`meeting/list`, { params: data });
};

export const getDetailMeeting = (meeting) => {
  return axios.get(`meeting/${meeting}/detail`);
};

export const getUserByEmail = (data) => {
  return axios.get(`user_by_email`, { params: data });
};

export const getDetailMeetingByLinkID = (data) => {
  return axios.get(`meeting/detail`, { params: data });
};

export const createMeeting = (data) => {
  return axios.post(`/meeting/create`, data);
};

export const inviteUser = (data) => {
  return axios.post(`/account/invite`, data);
};

export const updateMeeting = (meeting, data) => {
  return axios.post(`/meeting/${meeting}/update`, data);
};

export const updateParicipant = (participant, data) => {
  return axios.post(`/participant/${participant}/update`, data);
};

export const updateStatusParticipant = (participant, data) => {
  return axios.post(`/participant/${participant}/update/status`, data);
};

export const deleteMeeting = (meeting) => {
  return axios.post(`/meeting/${meeting}/delete`);
};

export const deleteEmail = (meetingID, participantID) => {
  return axios.post(`/meeting/${meetingID}/participant/delete`, {
    participant_id: participantID,
  });
};

export const getListUserRelated = (props) => {
  return axios.get(`/user/list/related?${props}`);
};

export const getListGuestRelated = (props) => {
  return axios.get(`/user/list/related/guest?${props}`);
};

export const updateParticipantRole = (data) => {
  return axios.post("/participant/update/role", data);
};

export const deleteParticipant = (data) => {
  return axios.post("/participant/delete/list", data);
};

export const joiningMeeting = (data) => {
  return axios.post("/meeting/join", data);
};
