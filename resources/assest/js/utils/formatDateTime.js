import moment from "moment";
moment.locale("vn");


// Format time
export const formatTime = (time) => {
  if (!time) {
    return;
  }
  return moment(time).format("HH:mm");
};

/**
 * @description: format date YYYY-MM-DD
 * @param  {time} dateTime
 * @return {time}
 */
export const formatYMD = (dateTime) => {
  return moment(dateTime).format("YYYY-M-D");
};

/**
 * @description: format date time YYYY-MM-DD hh:mm
 * @param  {time} dateTime
 * @return {time}
 */
export const formatYMDhm = (dateTime) => {
  return moment(dateTime).format("YYYY-MM-DD HH:mm");
};

/**
 * @description: format date time hh:mm
 * @param  {time} dateTime
 * @return {time}
 */
export const formatHm = (dateTime) => {
  if (dateTime) return dateTime.slice(0, 5);
};

/**
 * @description get fromTime, toTime
 */
export const getMomentTime = (timeStart, timeEnd, newTime) => {
  const time = moment(newTime, "HH:mm");
  const start = moment(timeStart, "HH:mm");
  const end = moment(timeEnd, "HH:mm");
  return {
    time,
    start,
    end,
  };
};
