const meeting = {
  namespaced: true,
  state: {
    checkActive: null,
    upload_files: [],
    isErrorParticipant: false,
    isErrorTitle: false,
    dataInforMeeting: {},
    pdf: false,
    isSubmit: false,
    inputCount: 1,
    indexError: [],
    checkAdd: false,
    indexAdd: null,
    checkSubtract: false,
    indexSubtract: [],
    listError: [],
    id: null,
    files: [],
    checkID: false,
    showDetail: false,
    showButtonSaveTemp: true,
    linkID: "",
    linkWeb: null,
    detailParticipants: [],
    checkAPI: false,
    link: null,
    isUpdateParticipant: false,
  },
  mutations: {
    setLink(state, link) {
      state.link = link;
    },
    setCheckAPI(state, checkAPI) {
      state.checkAPI = checkAPI;
    },
    setDataDetail(state, detailParticipants) {
      state.detailParticipants = detailParticipants;
    },
    pushDataDetail(state, detailParticipants) {
      state.detailParticipants.push(detailParticipants);
    },
    setShowDetailMeeting(state, showDetail) {
      state.showDetail = showDetail;
    },
    setLinkID(state, linkID) {
      state.linkID = linkID;
    },
    setLinkWeb(state, linkWeb) {
      state.linkWeb = linkWeb;
    },
    setShowButtonTemp(state, showButtonSaveTemp) {
      state.showButtonSaveTemp = showButtonSaveTemp;
    },
    setCheckID(state, checkID) {
      state.checkID = checkID;
    },
    setEmptyFiles(state, files) {
      state.files = files;
    },
    setFiles(state, files) {
      state.files.push(files);
    },
    setID(state, id) {
      state.id = id;
    },
    setCheckSubtract(state, checkSubtract) {
      state.checkSubtract = checkSubtract;
    },
    pushIndexSubtract(state, indexSubtract) {
      state.indexSubtract.push(indexSubtract);
    },
    setIndexSubtract(state, indexSubtract) {
      state.indexSubtract = indexSubtract;
    },
    setIndexAdd(state, indexAdd) {
      state.indexAdd = indexAdd;
    },
    setCheckAdd(state, checkAdd) {
      state.checkAdd = checkAdd;
    },
    setSubmit(state, isSubmit) {
      state.isSubmit = isSubmit;
    },
    setInputCount(state, inputCount) {
      state.inputCount = inputCount;
    },
    setIndexError(state, indexError) {
      state.indexError = indexError;
    },
    setPDF(state, pdf) {
      state.pdf = pdf;
    },
    setDataInforMeeting(state, dataInforMeeting) {
      state.dataInforMeeting = dataInforMeeting;
    },
    setErrorParticipant(state, isErrorParticipant) {
      state.isErrorParticipant = isErrorParticipant;
    },
    setErrorTitle(state, isErrorTitle) {
      state.isErrorTitle = isErrorTitle;
    },
    setCheckActive(state, checkActive) {
      state.checkActive = checkActive;
    },
    setUploadFiles(state, upload_files) {
      state.upload_files = upload_files;
    },
    pushUploadFiles(state, upload_files) {
      state.upload_files.push(upload_files);
    },
    spliceUploadFiles(state, upload_files) {
      state.upload_files.splice(upload_files, 1);
    },
    spliceFiles(state, files) {
      state.files.splice(files, 1);
    },
  },
};

export default meeting;
