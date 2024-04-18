<template>
  <div :class="[isActive ? 'active' : '', 'container']" id="container">
    <div class="form-container sign-up">
      <form @submit.prevent="handleSignUp">
        <h1>Create Account</h1>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
        <span>or use your email for registeration</span>
        <input type="text" placeholder="Name" />
        <input type="email" v-model="usernameSignUp" placeholder="Email" />
        <input type="password" v-model="passwordSignUp" placeholder="Password" />
        <button>Đăng ký</button>
      </form>
    </div>
    <div class="form-container sign-in">
      <form @submit.prevent="handleSignIn">
        <h1>Đăng nhập</h1>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
        <input type="email" v-model="usernameSignIn" placeholder="Email" />
        <input type="password" v-model="passwordSignIn" placeholder="Password" />
        <a href="#">Forget Your Password?</a>
        <button>Đăng nhập</button>
      </form>
    </div>
    <div class="toggle-container">
      <div class="toggle bg-color-primary">
        <div class="toggle-panel toggle-left">
          <h1>Welcome Back!</h1>
          <p>Enter your personal details to use all of site features</p>
          <button class="bg-color-green" id="login" @click="showSignUp">Đăng nhập</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Chào mừng bạn!</h1>
          <p>Đăng ký để xem nhiều thủ thuật , ưu đãi chỉ có riêng ở đây</p>
          <button class="bg-color-green" id="register" @click="showSignIn">
            Đăng ký
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";

export default {
  name: "Login",
  setup() {
    return {};
  },
};
</script>
<style lang="scss" scoped></style>
