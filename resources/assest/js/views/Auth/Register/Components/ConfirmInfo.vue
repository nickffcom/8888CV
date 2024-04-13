<template lang="">
  <form class="authentication-form-input lcf-username">
    <span class="fz-24 font-weight-600">メールをご確認ください</span>
    <span class="fz-24 font-weight-600 mt-20">登録まであと少し!</span>
    <span class="fz-14 font-weight-300 mt-40 text-center pl-40 pr-40">
      ご入力いただいたメールアドレスにアカウント登録のご案内をお送りしました。
    </span>
    <span class="fz-14 font-weight-300 mt-20 text-center pl-40 pr-40">
      下記の件名をご確認ください。
    </span>
    <div class="chat-meeting h-45-px mt-40">
      件名 ｜【Chatmeeting 】アカウント登録のご案内
    </div>
    <div
      class="d-flex mt-30 w-100 align-items-center h-45-px bg-color-primary pl-20"
    >
      <span class="text-white fz-14">登録完了までの流れ</span>
    </div>
    <div class="d-flex mt-30 w-100 align-items-center h-45-px">
      <div class="number-step">1</div>
      <span class="text-global fz-14 ml-15">
        メール内の「アカウント登録ボタン」をクリック
      </span>
    </div>
    <div class="d-flex w-100 align-items-center h-45-px">
      <div class="number-step">2</div>
      <span class="text-global fz-14 ml-13">
        「アカウント登録ページ」で必要事項を入力
      </span>
    </div>
  </form>
</template>
<script>
export default {
  methods: {},
};
</script>
<style lang="scss" scoped>
.number-step {
  border-radius: 50px;
  font-size: 12px;
  width: 25px;
  height: 25px;
  color: $white;
  background-color: $primary-color;
  display: flex;
  align-items: center;
  justify-content: center;
}
.chat-meeting {
  color: $black1;
  letter-spacing: 3px;
  font-size: 14px;
  width: 100%;
  border: 1px solid $white3;
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 60px;
  font-weight: 500;
}
</style>
