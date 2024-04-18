export const HOME_ROUTE = "/";
export const LOCAL_STORAGE_REFRESHTOKEN = "REFRESH_TOKEN";
export const LOCAL_STORAGE_TOKEN = "ACCESS_TOKEN";
export const LOCAL_STORAGE_ISLOGIN = "isLogin";
export const LOGIN_ROUTE = "/login";
export const ADMIN_DASHBOARD_ROUTE = "/admin";
export const LOCAL_STORAGE_USER_INFO = "USER_INFO";
export const ADMIN_ROLE = "ADMIN_ROLE";
export const STAFF_ROLE = "STAFF_ROLE";
export const LOCAL_STORAGE_ISROLEADMIN = "isRoleAdmin";
export const USER_ID_ADMIN = 1;
export const IS_LOGIN = "IS_LOGIN";

export const ERROR_MESSAGE = {
  loginError: "メールアドレス又はパスワードが違います。",
  emailError: "メールアドレスを入力してください。",
  emailEmptyError: "メールアドレスを入力してください。",
  emailSyntaxError: "メールアドレスが不正です。",
  emailExist: "メールアドレスは既に存在しています。",
  emailNotExist: "メールアドレスが存在していません。",
  passwordError: "パスワードは正しい形式を入力してください。",
  confirmEmailError: "メールアドレスが一致しません。",
  confirmPasswordError: "パスワードと確認パスワードが一致しません。",
  phoneError: "電話番号が不正です。",
  katakanaError: "カタカナを入力してください。",
  characterError: "半額英数字を入力してください。",
  timeError: "開始時間には終了時間以前の時間を指定してください。",
  timeFormatError: "時刻の形式が正しくありません。",
  timeValidation: "時刻の形式が正しくありません。",
  invalidPostalCode: "郵便番号は不正です。",
  emptyInput: "必須項目です。",
  checkToEditMeeting: "ミーティングを選択してください。",
  emailDuplicates: "現在のメールアドレスと一致しています。",
  errorDuplicateEmail: "メールアドレス一致しています。",
  passwordDuplicates: "現在のパスワードと一致しています。",
  wrongPassword: "現在のパスワードが違います。",
  emailEmptyAndError: "メールアドレスは正しい形式を入力してください。",
};

export const NOTIFICATION_MESSAGE = {
  CREATE_FAIL: "保存に失敗しました。",
  SAVE_TEMPORARY_FAIL: "一時保存に失敗しました",
  UPDATE_FAIL: "保存に失敗しました。",
  DELETE_FAIL: "削除に失敗しました。",
  RESTORE_FAIL: "復元に失敗しました",
  CREATE_SUCCESS: "作成しました。",
  SAVE_TEMPORARY_SUCCESS: "一時保存しました。",
  UPDATE_SUCCESS: "変更しました。",
  DELETE_SUCCESS: "削除しました。",
  RESTORE_SUCCESS: "復元しました。",
  CREATE_SUCCESS_MEETING: "保存しました。",
  GUEST_ERROR_CHAT: "ゲストはチャットできません",
  CREATOR_WHITOUT_CHAT:
    "ユーザーは現在のチャットミーティングに参加していません。",
  INVITE_SUCCESS: "招待しました。",
  INVITE_FAIL: "招待に失敗しました。",
  COPY_SUCCESS: "コピーしました。",
  SAVE_PROFILE_FALSE: "写真の更新を完了してください。",
};

export const ACCOUNT_CONTENT = {
  USER_INFORMATION: "ユーザー情報",
  CHANGED_NAME: "登録名変更",
  CHANGED_EMAIL: "メールアドレス変更",
  CHANGED_PASSWORD: "パスワード変更",
};

export const INVITATION_CONTENT = {
  INVITATION: "招待する",
  FIND_ACQUAINTANCES: "知り合いを探す",
  CONTACT_LIST: "コンタクト一覧",
  LOBBY: "未追加",
};

export const BUTTONS_GROUP = {
  SAVE_TEMPORARY: "一時保存",
  CANCEL: "キャンセル",
  SAVE: "確認する",
  RETURN: "戻る",
  CONFIRM_CREATING_MEETING: "資料配布する",
};

export const EMPTY_TEXT = "表示情報はありません。";

export const REGEX = {
  emailPattern: /^[^ ]+@[^ ]+\.[a-z]{2,3}$/,
  passwordPattern: /^(?=.*[a-zA-Z])(?=.*[0-9])(?!^[ぁ-んァ-ン　]+$).{8,}$/,
};

export const STATUS_LIST_CHATS = {
  seen: "既読",
  notSeen: "未読",
  operator: "モデレーター",
};

export const POSITION = {
  operator: 0,
  guest: 1,
  member: 2,
};

export const POSITION_UI_MEETING = {
  fill: 0,
  confirm: 1,
  complete: 2,
};

export const PRIVACY_POLICY = {
  contact: 1,
  public: 2,
};

export const MEETING_STATUS = {
  draff: 0,
  normal: 1,
};

export const PARTICIPANT_ROLE = {
  guest: 0,
  manager: 1,
};

export const LOCATION = {
  webURL: { locationType: 1, value: "web会議" },
  meetingPlace: { locationType: 2, value: "打ち合わせ場所" },
  others: { locationType: 3, value: "その他" },
};

export const LOCATION_VALUE = {
  1: LOCATION.webURL.value,
  2: LOCATION.meetingPlace.value,
  3: LOCATION.others.value,
};
